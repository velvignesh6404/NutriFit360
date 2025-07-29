<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$logs_stmt = $conn->prepare("
    SELECT wc.name AS category_name, wp.exercise_name, al.day, al.timestamp
    FROM admin_logs al
    JOIN workout_plans wp ON al.workout_plan_id = wp.id
    JOIN workout_categories wc ON wp.category_id = wc.id
    WHERE al.user_id = ?
    ORDER BY wc.name, al.day ASC, al.timestamp DESC
");
$logs_stmt->execute([$user_id]);
$logs = $logs_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Workout Progress</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .user-progress {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 1000px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .user-progress h1 {
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            font-size: 16px;
            color: #333;
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<?php include 'components/user_header.php' ?>

<div class="user-progress">
    <h1>Your Workout Progress</h1>
    <?php if (!empty($logs)): ?>
        <?php 
        $grouped_logs = [];
        foreach ($logs as $log) {
            $grouped_logs[$log['category_name']][] = $log;
        }
        ?>
        <?php foreach ($grouped_logs as $category_name => $category_logs): ?>
            <h2><?= htmlspecialchars($category_name); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Exercise Name</th>
                        <th>Day</th>
                        <th>Completion Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($category_logs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['exercise_name']); ?></td>
                            <td>Day <?= htmlspecialchars($log['day']); ?></td>
                            <td><?= htmlspecialchars($log['timestamp']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You haven't completed any workouts yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
