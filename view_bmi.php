<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$bmi_records = $conn->prepare("SELECT * FROM bmi_records WHERE user_id = ? ORDER BY created_at DESC");
$bmi_records->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your BMI History</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
.bmi-history {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    margin: 20px auto;
    max-width: 1000px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.bmi-history h1 {
    text-align: center;
    font-size: 28px;
    color: #333;
    margin-bottom: 20px;
}

.bmi-history table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.bmi-history th, .bmi-history td {
    padding: 12px 15px;
    text-align: center;
    border: 1px solid #ddd;
}

.bmi-history th {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
}

.bmi-history tr:nth-child(even) {
    background-color: #f2f2f2;
}

.bmi-history tr:hover {
    background-color: #ddd;
}

.bmi-history td {
    font-size: 16px;
    color: #333;
}

    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="bmi-history">
    <h1>Your BMI History</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Height (cm)</th>
                <th>Weight (kg)</th>
                <th>BMI</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $bmi_records->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['created_at']; ?></td>
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
