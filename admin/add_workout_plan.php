<?php

session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

if (!isset($_GET['category_id']) || empty($_GET['category_id'])) {
    echo "Category ID is required!";
    exit;
}
$category_id = $_GET['category_id'];

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $day = $_POST['day'];
    $exercise_name = $_POST['exercise_name'];
    $description = $_POST['description'];
    $tutorial_url = $_POST['tutorial_url'];

    if (empty($category_id)) {
        echo "Category ID cannot be empty.";
        exit;
    }

    // Check if the workout plan for this category and day already exists
    $stmt = $conn->prepare("SELECT * FROM workout_plans WHERE category_id = ? AND day = ?");
    $stmt->execute([$category_id, $day]);
    $existing_plan = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_plan) {
        $error_message = "A workout plan for Day $day already exists in this category. You cannot add it again.";
    }

    if (empty($error_message)) {
        $stmt = $conn->prepare("INSERT INTO workout_plans (category_id, day, exercise_name, description, tutorial_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$category_id, $day, $exercise_name, $description, $tutorial_url]);

        $success_message = "Workout plan added successfully!";
        header('Location: add_workout_plan.php?category_id=' . $category_id . '&success=true');
        exit;
    }
}

$stmt = $conn->prepare("SELECT * FROM workout_plans WHERE category_id = ?");
$stmt->execute([$category_id]);
$workout_plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $success_message = "Workout plan added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Workout Plan</title>
    <link rel="stylesheet" href="../css/admin_style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #fff;
            padding: 20px 0;
            font-size: 30px;
            background-color: #4caf50;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 0;
        }

        form {
            max-width: 600px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 2px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }

        input[type="number"], input[type="text"], input[type="url"] {
            height: 40px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        input:focus, textarea:focus {
            border-color: #4caf50;
            outline: none;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
            transform: translateY(-3px);
        }

        button:active {
            background-color: #388e3c;
        }

        .success-message {
            background-color: #4caf50;
            color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); 
        }

        table th, table td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #4caf50;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        table td {
            background-color: #f9f9f9;
            font-size: 14px;
        }

        table tr:hover {
            background-color: #e7f9e7;
        }

        /* Table Links */
        a {
            margin-right: 8px;
            text-decoration: none;
            color: #4caf50;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #388e3c;
        }

        @media (max-width: 600px) {
            table th, table td {
                padding: 6px 10px;
                font-size: 12px;
            }

            table th {
                font-size: 14px;
            }

            table td {
                font-size: 12px;
            }

            form {
                padding: 20px;
                margin: 20px;
            }

            h1 {
                font-size: 24px;
            }

            input, textarea {
                padding: 8px;
                font-size: 14px;
            }

            button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include '../components/admin_header.php'; ?>
    <h1>Add Workout Plan</h1>

    <?php if (!empty($success_message)): ?>
        <div class="success-message">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="error-message" style="color: red;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="day">Day:</label>
        <input type="number" name="day" id="day" required>

        <label for="exercise_name">Exercise Name:</label>
        <input type="text" name="exercise_name" id="exercise_name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="tutorial_url">Tutorial URL:</label>
        <input type="url" name="tutorial_url" id="tutorial_url" required>

        <button type="submit">Add Workout Plan</button>
    </form>

    <h2>Existing Workout Plans</h2>
    <table>
        <tr>
            <th>Day</th>
            <th>Exercise Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php foreach ($workout_plans as $plan): ?>
            <tr>
                <td><?= htmlspecialchars($plan['day']) ?></td>
                <td><?= htmlspecialchars($plan['exercise_name']) ?></td>
                <td><?= htmlspecialchars($plan['description']) ?></td>
                <td>
                    <a href="edit_workout_plan.php?plan_id=<?= $plan['id']; ?>&category_id=<?= $category_id; ?>">Edit</a>
                    <a href="delete_workout_plan.php?plan_id=<?= $plan['id']; ?>&category_id=<?= $category_id; ?>" onclick="return confirm('Are you sure you want to delete this workout plan?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
