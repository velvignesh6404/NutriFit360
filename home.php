<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>NutriFit360</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
   .hero .swiper-slide .image {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
   }
   .hero .swiper-slide .image img {
      width: 1000px;
      height: 550px;
   }

   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="about">
   <div class="row">
      <div class="content">
         <h3>Welcome to NutriFit360: <br>Your Partner in Health and Fitness</h3>
      </div>
   </div>
</section>

<section class="hero">
   <div class="swiper hero-slider">
         <div class="swiper-slide slide">
            <div class="image">
               <img src="images/fitness-hero.jpg" alt="Fitness Motivation">
            </div>
         </div>
   </div>
</section>

<section class="about">
   <div class="row">
      <div class="image">
         <img src="images/fitness-about.png" alt="About FitTrack">
      </div>
      <div class="content">
         <h3>About NutriFit360</h3>
         <p>NutriFit360 is designed to simplify your fitness journey. Whether you're aiming to lose weight, build muscle, or just maintain a healthy lifestyle, our platform provides tools to track, plan, and monitor your progress.</p>
         <ul>
            <li><i class="fas fa-check"></i> Personalized Dashboard: Tailored to your fitness and nutrition needs.</li>
            <li><i class="fas fa-check"></i> Customizable Plans: Create and adapt workout and meal plans to fit your goals.</li>
            <li><i class="fas fa-check"></i> All-in-One Solution: Monitor calories, hydration, workouts, and more in one place.</li>
         </ul>
         <p>With NutriFit360, achieving your health goals has never been easier. Take the first step towards a healthier you!</p>
         <a href="features.php" target="_blank" class="btn">Explore Features</a>
      </div>
   </div>
</section>

<section class="steps">
   <h1 class="title">Getting Started is Simple</h1>
   <div class="step-container">
      <div class="step">
         <i class="fas fa-user-plus"></i>
         <h3>Sign Up</h3>
         <p>Register to create your personalized fitness dashboard.</p>
      </div>
      <div class="step">
         <i class="fas fa-dumbbell"></i>
         <h3>Set Goals</h3>
         <p>Define your fitness objectives and track your progress.</p>
      </div>
      <div class="step">
         <i class="fas fa-chart-line"></i>
         <h3>Track & Improve</h3>
         <p>Log workouts, meals, and hydration to stay on track.</p>
      </div>
   </div>
</section>

<script src="js/script.js"></script>

<?php include 'components/footer.php'; ?>
</body>
</html>
