<?php
session_start();
include 'components/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$intake_amount = $_POST['intake_amount'];
$current_date = date('Y-m-d');

$stmt = $conn->prepare("SELECT id FROM water_intake WHERE user_id = ? AND date = ?");
$stmt->execute([$user_id, $current_date]);
$existing_record = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing_record) {
    $update_stmt = $conn->prepare("UPDATE water_intake SET intake_amount = intake_amount + ? WHERE id = ?");
    $update_stmt->execute([$intake_amount, $existing_record['id']]);
} else {
    $insert_stmt = $conn->prepare("INSERT INTO water_intake (user_id, date, intake_amount) VALUES (?, ?, ?)");
    $insert_stmt->execute([$user_id, $current_date, $intake_amount]);
}

echo 'success';
exit;
