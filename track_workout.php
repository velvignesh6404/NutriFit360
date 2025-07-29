<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['mark_completed'])) {
    try {
        $user_id = $_SESSION['user_id'];
        $workout_plan_id = $_POST['workout_plan_id'];
        $day = $_POST['day'];
        $category_id = $_POST['category_id'];

        $stmt = $conn->prepare("SELECT * FROM workout_plans WHERE id = ? AND day = ?");
        $stmt->execute([$workout_plan_id, $day]);
        $workout = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$workout) {
            header('Location: workout_plan.php?category_id=' . $category_id);
            exit;
        }

        $check_progress = $conn->prepare("SELECT * FROM user_workout_tracking WHERE user_id = ? AND workout_plan_id = ? AND day = ?");
        $check_progress->execute([$user_id, $workout_plan_id, $day]);

        if ($check_progress->rowCount() > 0) {
            $update_progress = $conn->prepare("UPDATE user_workout_tracking SET completed = 1 WHERE user_id = ? AND workout_plan_id = ? AND day = ?");
            $update_progress->execute([$user_id, $workout_plan_id, $day]);
        } else {
            $insert_progress = $conn->prepare("INSERT INTO user_workout_tracking (user_id, workout_plan_id, day, completed) VALUES (?, ?, ?, 1)");
            $insert_progress->execute([$user_id, $workout_plan_id, $day]);
        }

        $insert_admin_log = $conn->prepare("INSERT INTO admin_logs (user_id, workout_plan_id, day, action, timestamp) VALUES (?, ?, ?, 'completed', NOW())");
        $insert_admin_log->execute([$user_id, $workout_plan_id, $day]);

        $_SESSION['message'] = 'Day ' . $day . ' Completed';

        header('Location: workout_plan.php?category_id=' . $category_id);
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>
