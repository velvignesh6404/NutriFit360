<?php
session_start();
include 'components/connect.php';
include 'components/user_header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$category_id = $_GET['category_id'];

$workout_plan = $conn->prepare("SELECT * FROM workout_plans WHERE category_id = ? ORDER BY day");
$workout_plan->execute([$category_id]);

$completed_days_stmt = $conn->prepare("SELECT day FROM user_workout_tracking WHERE user_id = ? AND workout_plan_id IN (SELECT id FROM workout_plans WHERE category_id = ?) AND completed = 1");
$completed_days_stmt->execute([$_SESSION['user_id'], $category_id]);
$completed_days = $completed_days_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>30-Day Workout Plan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        h1 {
            text-align: center;
            font-size: 30px;
            color: #007BFF;
            margin-top: 40px;
            font-weight: bold;
        }

        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 16px;
        }

        td {
            background-color: #f9f9f9;
            font-size: 14px;
        }

        tr:hover {
            background-color: #e9f7fe;
        }

        button {
            padding: 10px 20px;
            font-size: 14px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-weight: bold;
        }

        button:hover {
            background-color: #4cae4c;
            transform: translateY(-3px);
        }

        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        a {
            font-size: 14px;
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease, text-shadow 0.3s ease;
        }

        a:hover {
            color: #0056b3;
            text-shadow: 0 0 5px #007BFF;
        }

        form {
            display: inline-block;
        }

        @media (max-width: 800px) {
            table {
                width: 90%;
            }

            th, td {
                font-size: 12px;
            }

            button {
                font-size: 12px;
                padding: 8px 15px;
            }

            h1 {
                font-size: 24px;
            }
        }

        @media (max-width: 500px) {
            table {
                width: 100%;
                margin: 20px auto;
            }

            h1 {
                font-size: 20px;
            }

            th, td {
                font-size: 12px;
            }

            button {
                font-size: 12px;
                padding: 8px 15px;
            }
        }

    </style>

</head>
<body>
    <h1>28-Day Workout Plan</h1>

    <?php
    if (isset($_SESSION['message'])) {
        echo '<p style="text-align: center; color: green; font-weight: bold;">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }

    if (isset($_GET['error']) && $_GET['error'] === 'previous_day_not_completed') {
        echo '<p style="text-align: center; color: red; font-weight: bold;">You must complete the previous day before proceeding.</p>';
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Exercise</th>
                <th>Description</th>
                <th>Tutorial</th>
                <th>Mark as Completed</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($workout = $workout_plan->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td>Day <?= $workout['day']; ?></td>
                    <td><?= $workout['exercise_name']; ?></td>
                    <td><?= $workout['description']; ?></td>
                    <td><a href="<?= $workout['tutorial_url']; ?>" target="_blank">View Tutorial</a></td>
                    <td>
                        <?php
                        $is_completed = in_array($workout['day'], $completed_days);
                        $button_disabled = $is_completed ? 'disabled' : '';
                        $button_text = $is_completed ? 'Completed' : 'Mark as Completed';
                        ?>
                        <form action="track_workout.php" method="POST">
                            <input type="hidden" name="workout_plan_id" value="<?= $workout['id']; ?>">
                            <input type="hidden" name="day" value="<?= $workout['day']; ?>">
                            <input type="hidden" name="category_id" value="<?= $category_id; ?>">
                            <button type="submit" name="mark_completed" <?= $button_disabled; ?>><?= $button_text; ?></button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div style="text-align: center; margin-top: 20px;">
    <a href="user_progress.php">
        <button style="padding: 10px 20px; background-color: #007BFF; color: white; font-size: 14px; border: none; border-radius: 6px; cursor: pointer; transition: background-color 0.3s ease;">
            Track your Progress
        </button>
    </a>
    </div>

</body>
</html>
