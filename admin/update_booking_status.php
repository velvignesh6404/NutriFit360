<?php
include '../components/connect.php';

if (isset($_POST['booking_id']) && isset($_POST['status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    $update_status = $conn->prepare("UPDATE `table_bookings` SET `booking_status` = :status WHERE `id` = :id");
    $update_status->bindParam(':status', $status);
    $update_status->bindParam(':id', $booking_id);
    $update_status->execute();

    header('location:booked_appointment.php');
}
?>
