<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $price, $pid]);

   $message[] = 'product updated!';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'images size is too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../uploaded_img/'.$old_image);
         $message[] = 'image updated!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
.products-table-container {
   max-width: 800px;
   margin: 0 auto;
   background-color: #f8f9fa;
   border-radius: 0.5rem;
   padding: 2rem;
   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
   overflow-x: auto;
}

.products-table-container table {
   width: 100%;
   border-collapse: collapse;
}

.products-table-container table td {
   padding: 1rem;
   font-size: 1.2rem;
   text-align: left;
}

.products-table-container table input[type="text"],
.products-table-container table input[type="number"],
.products-table-container table select {
   width: calc(100% - 1rem);
   padding: 0.7rem;
   font-size: 1.5rem;
   border: 1px solid #ced4da;
   border-radius: 0.25rem;
}

.products-table-container table input[type="file"] {
   display: block;
   margin-top: 0.5rem;
}

.products-table-container table td:first-child {
   font-weight: bold;
   background-color: black;
   color: #fff;
}

.products-table-container table tr:nth-child(even) {
   background-color: #f8f9fa;
}

.products-table-container table tr:hover {
   background-color: #e9ecef;
}

.products-table-container table .empty {
   width: 100%;
   text-align: center;
   font-size: 2rem;
   color: #333;
   padding: 2rem;
}

.products-table-container table td[colspan="2"] {
   text-align: center;
}

.products-table-container table .btn {
   display: inline-block;
   width: 130px;
   font-weight: bolder;
   margin: 5px;
   background-color: white;
   color: black;
   padding: 0.5rem 1rem;
   border: none;
   border-radius: 2rem;
   cursor: pointer;
   text-decoration: none;
}

.products-table-container table .btn:hover {
   background-color: grey;
}
   </style>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="update-product">

   <div class="products-table-container">
       <h3>Update Product</h3>
       <?php
          $update_id = $_GET['update'];
          $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
          $show_products->execute([$update_id]);
          if($show_products->rowCount() > 0){
             while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
       ?>
       <form action="" method="POST" enctype="multipart/form-data">
           <table>
               <tr>
                   <td>Dish Name</td>
                   <td><input type="text" required placeholder="Enter product name" name="name" maxlength="100" value="<?= $fetch_products['name']; ?>"></td>
               </tr>
               <tr>
                   <td>Dish Price</td>
                   <td><input type="number" min="0" max="9999999999" required placeholder="Enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_products['price']; ?>"></td>
               </tr>
               <tr>
                   <td>Category</td>
                   <td>
                       <select name="category" required>
                           <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
                           <option value="starters">Starters</option>
                           <option value="main dish">Main Dish</option>
                           <option value="drinks">Drinks</option>
                           <option value="desserts">Desserts</option>
                       </select>
                   </td>
               </tr>
               <tr>
                   <td>Image</td>
                   <td>
                       <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp">
                   </td>
               </tr>
               <tr>
                   <td colspan="2" style="text-align: center;">
                       <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                       <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
                       <input type="submit" value="Update" class="btn" name="update">
                       <a href="products.php" class="btn">Go Back</a>
                   </td>
               </tr>
           </table>
       </form>
       <?php
             }
          }else{
             echo '<p class="empty">No products added yet!</p>';
          }
       ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
