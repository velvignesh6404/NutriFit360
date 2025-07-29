<?php
session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

if (!isset($_GET['plan_id']) || empty($_GET['plan_id'])) {
    echo "Plan ID is required!";
    exit;
}

$plan_id = $_GET['plan_id'];
$category_id = $_GET['category_id'];

$success_message = '';
$error_message = '';

$stmt = $conn->prepare("SELECT * FROM workout_plans WHERE id = ? AND category_id = ?");
$stmt->execute([$plan_id, $category_id]);
$plan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plan) {
    echo "Workout plan not found!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $day = $_POST['day'];
    $exercise_name = $_POST['exercise_name'];
    $description = $_POST['description'];
    $tutorial_url = $_POST['tutorial_url'];

    if (empty($exercise_name) || empty($description)) {
        $error_message = "Please fill in all fields.";
    }

    if (empty($error_message)) {
        $stmt = $conn->prepare("UPDATE workout_plans SET day = ?, exercise_name = ?, description = ?, tutorial_url = ? WHERE id = ?");
        $stmt->execute([$day, $exercise_name, $description, $tutorial_url, $plan_id]);

        $success_message = "Workout plan updated successfully!";

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Workout Plan</title>
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

.success-message, .error-message {
    padding: 15px;
    margin: 20px 0;
    text-align: center;
    font-weight: bold;
    border-radius: 6px;
}

.success-message {
    background-color: #4caf50;
    color: white;
}

.error-message {
    background-color: #f44336;
    color: white;
}

@media (max-width: 600px) {
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

    <h1>Edit Workout Plan</h1>

    <?php if (!empty($success_message)): ?>
        <div class="success-message"><?= $success_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= $error_message; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="day">Day:</label>
        <input type="number" name="day" id="day" value="<?= htmlspecialchars($plan['day']); ?>" required>

        <label for="exercise_name">Exercise Name:</label>
        <input type="text" name="exercise_name" id="exercise_name" value="<?= htmlspecialchars($plan['exercise_name']); ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($plan['description']); ?></textarea>

        <label for="tutorial_url">Tutorial URL:</label>
        <input type="url" name="tutorial_url" id="tutorial_url" value="<?= htmlspecialchars($plan['tutorial_url']); ?>" required>

        <button type="submit">Update Workout Plan</button>
    </form>
</body>
</html>
