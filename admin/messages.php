<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
.messages-table-container {
   max-width: 800px;
   margin: 0 auto;
   background-color: #f8f9fa;
   border-radius: 0.5rem;
   padding: 2rem;
   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
   overflow-x: auto;
}

.messages-table-container table {
   width: 100%;
   border-collapse: collapse;
}

.messages-table-container table th,
.messages-table-container table td {
   padding: 1rem;
   font-size:1.3rem;
   text-align: left;
}

.messages-table-container table th {
   background-color: black;
   font-size: 1.5rem;
   font-weight:bold;
   color: #fff;
}

.messages-table-container table tr:nth-child(even) {
   background-color: #f8f9fa;
}

.messages-table-container table .empty {
   width: 100%;
   text-align: center;
   font-size: 1.8rem;
   color: red;
   padding: 2rem;
}

.messages-table-container table td:last-child {
   text-align: center;
}

.messages-table-container table .delete-btn {
   padding: 0.2rem 0.7rem;
   background-color: #dc3545;
   color: #fff;
   border: none;
   border-radius: 0.5rem;
   cursor: pointer;
   text-decoration: none;
}

.messages-table-container table .delete-btn:hover {
   background-color: darkred;
}
   </style>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="messages">

   <div class="messages-table-container">
       <h1 class="heading">Messages</h1>
       <table>
           <tr>
               <th>Name</th>
               <th>Number</th>
               <th>Email</th>
               <th>Message</th>
               <th>Delete  </th>
           </tr>
           <?php
              $select_messages = $conn->prepare("SELECT * FROM `messages`");
              $select_messages->execute();
              if($select_messages->rowCount() > 0){
                 while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
           ?>
           <tr>
               <td><?= $fetch_messages['name']; ?></td>
               <td><?= $fetch_messages['number']; ?></td>
               <td><?= $fetch_messages['email']; ?></td>
               <td><?= $fetch_messages['message']; ?></td>
               <td>
                   <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Delete this message?');">Delete</a>
               </td>
           </tr>
           <?php
                 }
              }else{
                 echo '<tr><td colspan="5" class="empty">You have no messages</td></tr>';
              }
           ?>
       </table>
   </div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
