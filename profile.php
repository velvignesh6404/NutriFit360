<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

// Fetch user details from the database
$fetch_profile = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="user-details">
   <div class="user">
      <p><i class="fas fa-user"></i><span><span><?= $fetch_profile['name']; ?></span></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number']; ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email']; ?></span></p>
      <a href="update_profile.php" class="btn">Update Login Info</a>

      <p class="details"><i class="fas fa-birthday-cake"></i><span>
         <?php if ($fetch_profile['age'] == '') {
            echo 'Please enter your age';
         } else {
            echo $fetch_profile['age'] . ' years';
         } ?>
      </span></p>

      <p class="details"><i class="fas fa-ruler-vertical"></i><span>
         <?php if ($fetch_profile['height'] == '') {
            echo 'Please enter your height';
         } else {
            echo $fetch_profile['height'] . ' cm';
         } ?>
      </span></p>

      <p class="details"><i class="fas fa-weight"></i><span>
         <?php if ($fetch_profile['weight'] == '') {
            echo 'Please enter your weight';
         } else {
            echo $fetch_profile['weight'] . ' kg';
         } ?>
      </span></p>

      <p class="details"><i class="fas fa-bullseye"></i><span>
         <?php if ($fetch_profile['goal'] == '') {
            echo 'Please set your goal';
         } else {
            echo ucfirst($fetch_profile['goal']);
         } ?>
      </span></p>

      <p class="details"><i class="fas fa-utensils"></i><span>
         <?php if ($fetch_profile['food_preference'] == '') {
            echo 'Please select your food preference';
         } else {
            echo ucfirst($fetch_profile['food_preference']);
         } ?>
      </span></p>

      <a href="update_details.php" class="btn">Add/Update User Data</a>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
