<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $height_cm = $_POST['height'];
    $weight = $_POST['weight'];

    $height = $height_cm / 100;

    $bmi = $weight / ($height * $height);
    $bmi_category = '';

    if ($bmi < 18.5) {
        $bmi_category = 'Underweight';
    } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
        $bmi_category = 'Normal';
    } elseif ($bmi >= 25 && $bmi <= 29.9) {
        $bmi_category = 'Overweight';
    } else {
        $bmi_category = 'Obesity';
    }

    $save_bmi = $conn->prepare("INSERT INTO bmi_records (user_id, age, gender, height, weight, bmi, bmi_category) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $save_bmi->execute([$user_id, $age, $gender, $height_cm, $weight, $bmi, $bmi_category]);

    $_SESSION['message'] = "Your BMI is " . round($bmi, 2) . " ($bmi_category).";
    header('Location: view_bmi.php');
    exit;
}
?>
