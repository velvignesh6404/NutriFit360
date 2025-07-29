<?php
include 'components/connect.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
    color: #333;
}

header {
    background-color: #007BFF;
    color: #fff;
    padding: 15px 20px;
    text-align: center;
}

.bmi-calculator {
    max-width: 500px;
    margin: 50px auto;
    background-color: #5cb85c;
    padding: 30px 25px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    text-align: center;
    transition: transform 0.3s ease;
}

.bmi-calculator h1 {
    font-size: 26px;
    margin-bottom: 25px;
    color: black;
    font-weight: bold;
    text-transform: uppercase;
}

.bmi-calculator a {
    display: inline-block;
    margin-top: 15px;
    font-size: 16px;
    color: black;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease, text-shadow 0.3s ease;
    text-decoration: underline;
}

.bmi-calculator a:hover {
    color: white;
}

form {
    display: flex;
    flex-direction: column;
    align-items: stretch;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

label {
    display: inline;
    margin-bottom: 4px;
    font-weight: bold;
    font-size: medium;
    color: black;
}

input[type="number"],
input[type="radio"] {
    padding: 12px;
    width: 100%;
    border: 2px solid #4cae4c;
    border-radius: 6px;
    font-size: 14px;
    background-color: #fff;
    color: #333;
    transition: border-color 0.3s ease;
}

input[type="radio"] {
    width: auto;
    margin-right: 5px;
}

input[type="number"]:focus {
    border-color: #007BFF;
    outline: none;
}

button {
    padding: 12px;
    font-size: 16px;
    background-color: black;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    font-weight: bold;
}

button:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

@media (max-width: 600px) {
    .bmi-calculator {
        margin: 20px;
        padding: 20px;
    }

    .bmi-calculator h1 {
        font-size: 22px;
    }
}


    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="bmi-calculator">
    <h1>BMI Calculator</h1>
    <form id="bmi-form" method="POST" action="save_bmi.php">
        <div class="form-group">
            <label for="age">Age</label>
            <input type="number" id="age" name="age" min="1" max="120" required>
        </div>
        <div class="form-group">
            <label>Gender : </label>
            <input type="radio" id="male" name="gender" value="male" required>
            <label for="male">Male</label>
            <input type="radio" id="female" name="gender" value="female" required>
            <label for="female">Female</label>
        </div>
        <div class="form-group">
            <label for="height">Height (centimeters)</label>
            <input type="number" id="height" name="height" step="1" min="50" max="250" required>
        </div>

        <div class="form-group">
            <label for="weight">Weight (kilograms)</label>
            <input type="number" id="weight" name="weight" step="0.1" min="10" max="300" required>
        </div>
        <button type="submit" class="btn">Calculate BMI</button>
        <a href="view_bmi.php">View Calculated BMI</a>
    </form>
</section>

</body>
</html>
