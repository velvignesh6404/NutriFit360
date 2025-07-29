<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   
   if($select_admin->rowCount() > 0){
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      $_SESSION['admin_id'] = $fetch_admin_id['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
body {
   font-family: Arial, sans-serif;
   display: flex;
   justify-content: center;
   align-items: center;
   height: 100vh;
   margin: 0;
}

.form-container {
   background: linear-gradient(135deg, #e17055, #f6e58d);
   width: 350px;
   background-color: rgba(255, 255, 255, 0.9);
   border-radius: 10px;
   padding: 30px;
   box-shadow: 0px 0px 20px 0px rgba(0,0,0,0.1);
}

.form-container h3 {
   text-align: center;
   color: #333;
   margin-bottom: 20px;
}

.form-container input[type="text"],
.form-container input[type="password"] {
   width: 100%;
   padding: 10px;
   margin-bottom: 20px;
   border: 1px solid #ccc;
   border-radius: 5px;
   box-sizing: border-box;
}

.form-container input[type="submit"] {
   display: inline-block;
   text-decoration: none;
   color: white;
   padding: 8px 15px; 
   border: 1px solid #333; 
   border-radius: 5px;
   background-color: black;
   font-size: 16px;
   cursor: pointer;
   transition: background-color 0.3s ease;
}


.message {
   position: fixed;
   top: 20px;
   right: 20px;
   background-color: #f8d7da;
   color: #721c24;
   padding: 10px 20px;
   border-radius: 5px;
   box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
   display: flex;
   align-items: center;
}

.message span {
   margin-right: 10px;
}

.message i {
   cursor: pointer;
}


   </style>

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">

   <form action="" method="POST">
      <h3>Login</h3>
      <input type="text" name="name" maxlength="20" required placeholder="enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" name="submit" class="btn">
   </form>

</section>

</body>
</html>