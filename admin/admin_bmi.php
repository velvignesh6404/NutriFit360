<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$bmi_records = $conn->prepare("SELECT bmi_records.*, users.name, users.email FROM bmi_records JOIN users ON bmi_records.user_id = users.id ORDER BY bmi_records.created_at DESC");
$bmi_records->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All BMI Records</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
    .admin-bmi-records {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        margin: 20px auto;
        max-width: 1000px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .admin-bmi-records h1 {
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 20px;
    }

    .admin-bmi-records table {
         width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .admin-bmi-records th, .admin-bmi-records td {
        padding: 12px 15px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .admin-bmi-records th {
        background-color: #4CAF50;
        color: white;
        font-weight: bold;
    }

    .admin-bmi-records tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .admin-bmi-records tr:hover {
        background-color: #ddd;
    }

    .admin-bmi-records td {
        font-size: 16px;
        color: #333;
    }
    </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="admin-bmi-records">
    <h1>BMI Records</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Height (m)</th>
                <th>Weight (kg)</th>
                <th>BMI</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $bmi_records->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['created_at']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['age']; ?></td>
                    <td><?= ucfirst($row['gender']); ?></td>
                    <td><?= $row['height']; ?></td>
                    <td><?= $row['weight']; ?></td>
                    <td><?= round($row['bmi'], 2); ?></td>
                    <td><?= $row['bmi_category']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

<script src="js/script.js"></script>
</body>
</html>
