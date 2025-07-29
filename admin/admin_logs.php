<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$logs_stmt = $conn->prepare("SELECT u.name AS user_name, wp.exercise_name, al.day, al.timestamp
                             FROM admin_logs al
                             JOIN users u ON al.user_id = u.id
                             JOIN workout_plans wp ON al.workout_plan_id = wp.id
                             ORDER BY al.timestamp DESC");
$logs_stmt->execute();
$logs = $logs_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

.admin-dashboard {
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    margin: 20px auto;
    max-width: 1000px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

h1 {
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
<?php include '../components/admin_header.php' ?>

    <h1>User Workout Data</h1>
    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Exercise Name</th>
                <th>Day</th>
                <th>Completion Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['user_name']); ?></td>
                    <td><?= htmlspecialchars($log['exercise_name']); ?></td>
                    <td>Day <?= htmlspecialchars($log['day']); ?></td>
                    <td><?= htmlspecialchars($log['timestamp']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
