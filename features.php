<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Features</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      body {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
         background: #f4f4f4;
      }

      .container {
         max-width: 1200px;
         margin: 20px auto;
         padding: 20px;
      }

      .features-title {
         text-align: center;
         margin-bottom: 20px;
      }

      .features-title h1 {
         font-size: 2.5rem;
         color: #333;
      }

      .cards-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
         gap: 20px;
      }

      .card {
         background: #fff;
         border-radius: 8px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         overflow: hidden;
         text-align: center;
         transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .card:hover {
         transform: translateY(-10px);
         box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
      }

      .card i {
         font-size: 3rem;
         color: #5cb85c;
         margin: 20px 0;
      }

      .card h3 {
         font-size: 1.5rem;
         color: #333;
         margin-bottom: 10px;
      }

      .card p {
         font-size: 1rem;
         color: #666;
         margin: 0 15px 20px;
      }

      .card a {
         display: inline-block;
         margin: 10px 0 20px;
         padding: 10px 20px;
         background: #5cb85c;
         color: #fff;
         text-decoration: none;
         border-radius: 5px;
         transition: background 0.3s ease;
      }

      .card a:hover {
         background: #4cae4c;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="container">
   <div class="features-title">
      <h1>Explore Our Features</h1>
      <p>Take control of your health and fitness journey with these tools.</p>
   </div>
   <div class="cards-container">

      <!-- BMI Calculator Card -->
      <div class="card">
         <i class="fas fa-calculator"></i>
         <h3>BMI Calculator</h3>
         <p>Calculate your Body Mass Index (BMI) to assess your health status.</p>
         <a href="bmi_calculator.php">Use Now</a>
      </div>

      <!-- Workout Plans Card -->
      <div class="card">
         <i class="fas fa-dumbbell"></i>
         <h3>Workout Plans</h3>
         <p>Explore customizable workout routines tailored to your fitness goals.</p>
         <a href="workout_categories.php">Explore</a>
      </div>

      <!-- Water Intake Tracker Card -->
      <div class="card">
         <i class="fas fa-glass-water"></i>
         <h3>Water Intake Tracker</h3>
         <p>Monitor your daily water consumption and stay hydrated.</p>
         <a href="water_intake.php">Track Now</a>
      </div>

      <!-- Calorie Tracker Card -->
      <div class="card">
         <i class="fas fa-fire"></i>
         <h3>Calorie Tracker</h3>
         <p>Log your daily calorie intake and track your progress.</p>
         <a href="underm.html">Track Now</a>
      </div>

   </div>
</div>

<?php include 'components/footer.php'; ?>

</body>
</html>
