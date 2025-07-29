<?php

session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = htmlspecialchars(trim($_POST['category_name']));
    $category_desc = htmlspecialchars(trim($_POST['category_desc']));

    try {
        // Check if the category already exists
        $check_query = $conn->prepare("SELECT COUNT(*) FROM workout_categories WHERE name = :name");
        $check_query->bindParam(':name', $category_name);
        $check_query->execute();
        $existing_count = $check_query->fetchColumn();

        if ($existing_count > 0) {
            // If the category already exists, set an error message
            $_SESSION['error_message'] = "Category with this name already exists!";
            header('Location: admin_addworkouts.php');
            exit;
        }

        // Begin transaction for insertion
        $conn->beginTransaction();

        $category_query = $conn->prepare("INSERT INTO workout_categories (name, description) VALUES (:name, :description)");
        $category_query->bindParam(':name', $category_name);
        $category_query->bindParam(':description', $category_desc);
        $category_query->execute();

        $conn->commit();

        $_SESSION['success_message'] = "Category added successfully!";
        header('Location: admin_addworkouts.php');
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error_message'] = "Error adding category: " . $e->getMessage();
        header('Location: admin_addworkouts.php');
        exit;
    }
}
?>
