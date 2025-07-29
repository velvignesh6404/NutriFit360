<?php
include 'components/connect.php';
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

if (!isset($_SESSION['user_id'])) {
    $user_id = ''; // Default to empty if not logged in
} else {
    $user_id = $_SESSION['user_id'];
}
?>
<header class="header">
   <section class="flex">
      <a href="home.php" style="display: inline-block;">
         <img src="images/NutiFit360-logo.png" alt="Logo" style="width: 100px; height: auto;">
      </a>
      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="features.php">Features</a>
         <a href="booking.php">Schedule a Call</a>
         <a href="contact.php">Contact</a>
      </nav>

      <div class="icons">
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
         if (!empty($user_id)) {
             $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
             $select_profile->execute([$user_id]);
             if ($select_profile->rowCount() > 0) {
                 $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
                 <p class="name"><?= htmlspecialchars($fetch_profile['name']); ?></p>
                 <div class="flex">
                     <a href="profile.php" class="btn">Profile</a>
                     <a href="components/user_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">Logout</a>
                 </div>
         <?php
             }
         } else {
         ?>
             <p class="name">Please login first!</p>
             <a href="login.php" class="btn">Login</a>
         <?php } ?>
      </div>
   </section>
</header>
