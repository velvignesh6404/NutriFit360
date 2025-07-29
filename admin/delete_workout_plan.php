<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

if (!isset($_GET['plan_id']) || empty($_GET['plan_id'])) {
    echo "Plan ID is required!";
    exit;
}

$plan_id = $_GET['plan_id'];
$category_id = $_GET['category_id'];

$stmt = $conn->prepare("DELETE FROM user_workout_tracking WHERE workout_plan_id = ?");
$stmt->execute([$plan_id]);

$stmt = $conn->prepare("DELETE FROM workout_plans WHERE id = ? AND category_id = ?");
$stmt->execute([$plan_id, $category_id]);

header('Location: add_workout_plan.php?category_id=' . $category_id);
exit;
