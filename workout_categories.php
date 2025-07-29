<?php

include 'components/connect.php';
include 'components/user_header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$query = $conn->prepare("SELECT * FROM workout_categories");
$query->execute();

$user_categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Workout Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        h1 {
            color: black;
            padding: 15px 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        main.flex-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #5cb85c;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease;
        }

        main.flex-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        main h1 {
            font-size: 26px;
            margin-bottom: 25px;
            color: black;
            font-weight: bold;
            text-transform: uppercase;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: inline-block;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: medium;
            color: black;
        }

        select {
            padding: 12px;
            width: 100%;
            border: 2px solid #4cae4c;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            color: #333;
            transition: border-color 0.3s ease;
            margin-bottom: 20px;
        }

        select:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            padding: 12px;
            font-size: 16px;
            background-color: black;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            font-weight: bold;
            width: 100%;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        form a {
            display: inline-block;
            margin-top: 15px;
            font-size: 16px;
            color: black;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease, text-shadow 0.3s ease;
            text-decoration: underline;
        }

        form a:hover {
            color: white;
            text-shadow: 0 0 5px #fff;
        }

        @media (max-width: 600px) {
            main.flex-container {
                margin: 20px;
                padding: 20px;
            }

            main h1 {
                font-size: 22px;
            }
        }

    </style>
</head>
<body>
    <h1>Workout Categories</h1>
    <main class="flex-container">
        <h1>Select Workout Category</h1>
        <form action="workout_plan.php" method="GET">
            <label for="category">Select Category:</label>
            <select name="category_id" id="category" required>
                <?php if (!empty($user_categories)): ?>
                    <?php foreach ($user_categories as $category): ?>
                        <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No categories available</option>
                <?php endif; ?>
            </select>
            <button type="submit">View Plan</button>
        </form>
    </main>
</body>
</html>
