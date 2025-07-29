<?php
session_start();
include 'components/connect.php';
include 'components/user_header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$current_date = date('Y-m-d');
$stmt = $conn->prepare("SELECT intake_amount FROM water_intake WHERE user_id = ? AND date = ?");
$stmt->execute([$user_id, $current_date]);
$current_intake = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt_total = $conn->prepare("SELECT SUM(intake_amount) AS total_intake FROM water_intake WHERE user_id = ? AND YEARWEEK(date, 1) = YEARWEEK(NOW(), 1)");
$stmt_total->execute([$user_id]);
$total_intake = $stmt_total->fetch(PDO::FETCH_ASSOC);

$stmt_target = $conn->prepare("SELECT water_target FROM users WHERE id = ?");
$stmt_target->execute([$user_id]);
$user_target = $stmt_target->fetch(PDO::FETCH_ASSOC);

$target = $user_target['water_target'] ?? 2000;

$stmt_history = $conn->prepare("SELECT date, intake_amount FROM water_intake WHERE user_id = ? AND date = ? ORDER BY id DESC");
$stmt_history->execute([$user_id, $current_date]);
$history = $stmt_history->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Intake Tracker</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #00bcd4, #8e44ad);
            margin: 0;
            padding: 0;
            color: #fff;
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-top: 50px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .water-tracker-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .water-info {
            margin-bottom: 30px;
        }

        .water-info p {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }

        .progress-container {
            position: relative;
            width: 100%;
            height: 30px;
            background: #f0f0f0;
            border-radius: 25px;
            margin-bottom: 20px;
        }

        .progress-bar {
            height: 100%;
            background: #4caf50;
            border-radius: 25px;
            width: 0; /* Dynamic width */
            transition: width 0.5s ease-in-out;
        }

        button {
            background-color: #f39c12;
            color: #fff;
            padding: 12px 25px;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin: 10px;
        }

        button:hover {
            background-color: #e67e22;
            transform: translateY(-3px);
        }

        .buttons-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .history-section {
            margin-top: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .history-section h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th, .history-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #fff;
        }

        .history-table th {
            background-color: #8e44ad;
            color: white;
            font-weight: bold;
        }

        .history-table tr:nth-child(even) {
            background-color: #f1f1f1;
            color: #333;
        }

        .history-table tr:hover {
            background-color: #dcdcdc;
            color: #333;
        }

        .history-table td {
            background-color: #fff;
            color: #333;
        }

    </style>
</head>
<body>
    <h1>Water Intake Tracker</h1>
    <div class="water-tracker-container">
        <div class="water-info">
            <p>Your Daily Water Target: <?php echo $target; ?> ml</p>
            <p>Water Intake Today: <span id="currentIntake"><?php echo $current_intake['intake_amount'] ?? 0; ?></span> ml</p>
        </div>
        
        <div class="progress-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
        
        <p id="congratulationsMessage" style="text-align: center; margin-top: 10px;"></p>

        <div class="buttons-container">
            <button onclick="addWaterIntake(100)">Add 100 ml</button>
            <button onclick="addWaterIntake(250)">Add 250 ml</button>
            <button onclick="addWaterIntake(500)">Add 500 ml</button>
            <button onclick="addWaterIntake(750)">Add 750 ml</button>
            <button onclick="addWaterIntake(1000)">Add 1000 ml</button>
        </div>

        <div class="history-section">
            <h3>Water Intake History</h3>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Amount (ml)</th>
                    </tr>
                </thead>
                <tbody id="historyList">
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const target = <?php echo $target; ?>;
        let totalIntake = <?php echo $current_intake['intake_amount'] ?? 0; ?>;
        const progressBar = document.getElementById('progressBar');
        const currentIntakeElement = document.getElementById('currentIntake');
        const historyList = document.getElementById('historyList');
        const congratulationsMessage = document.getElementById('congratulationsMessage');

        function updateProgress() {
            const percentage = (totalIntake / target) * 100;
            progressBar.style.width = `${percentage}%`;
            currentIntakeElement.textContent = totalIntake;
        }

        function showCongratulations() {
            if (totalIntake >= target) {
                congratulationsMessage.textContent = "Congratulations! You've reached your daily water intake target!";
                congratulationsMessage.style.color = "green";
                congratulationsMessage.style.fontWeight = "bold";
                congratulationsMessage.style.fontSize = "18px";
            }
        }

        const history = <?php echo json_encode($history); ?>;
        history.forEach(record => {
            const time = new Date(record.date).toLocaleTimeString();
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${time}</td>
                <td>${record.intake_amount}</td>
            `;
            historyList.appendChild(row);
        });

        function addWaterIntake(amount) {
            totalIntake += amount;
            if (totalIntake > target) totalIntake = target;

            updateProgress();
            addToHistory(amount);

            fetch('update_water_intake.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'intake_amount': amount
                })
            }).then(() => {
                showCongratulations();
            });
        }

        function addToHistory(amount) {
            const time = new Date().toLocaleTimeString();
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${time}</td>
                <td>${amount}</td>
            `;
            historyList.appendChild(row);
        }

        updateProgress();
    </script>
</body>
</html>
