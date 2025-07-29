<?php

session_start();
include '../components/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$categories = $conn->prepare("SELECT * FROM workout_categories");
$categories->execute();
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
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f9fb;
            color: #333;
        }

        h1, h2 {
            text-align: center;
            color: #4A90E2;
            margin-top: 40px;
        }

        h1 {
            font-size: 38px;
            font-weight: bold;
        }

        h2 {
            font-size: 26px;
            font-weight: normal;
        }

        h3 {
            font-size: 22px;
            color: #333;
            margin-top: 30px;
            text-align: center;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
        }

        .message {
            text-align: center;
            margin: 20px auto;
            padding: 15px;
            border-radius: 6px;
            width: 80%;
            max-width: 600px;
        }

        .message.success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }

        .message.error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin-top: 30px;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin: 20px;
            width: 250px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            font-size: 20px;
            color: #4A90E2;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            color: #777;
            margin-bottom: 15px;
        }

        .card a {
            display: inline-block;
            text-decoration: none;
            background-color: #4A90E2;
            color: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .card a:hover {
            background-color: #357ABD;
        }

        form {
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 600px;
        }

        form input, form textarea {
            padding: 12px;
            margin: 10px 0;
            width: 90%;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            background-color: #fafafa;
        }

        form button {
            padding: 12px 25px;
            background-color: #5cb85c;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        form button:hover {
            background-color: #4cae4c;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <?php include '../components/admin_header.php'; ?>

    <div class="container">
        <h1>Manage Workout Categories</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="message success">
                <?= $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php elseif (isset($_SESSION['error_message'])): ?>
            <div class="message error">
                <?= $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <h2>Categories Added</h2>
        
        <div class="card-container">
            <?php while ($category = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="card">
                    <h3><?= $category['name']; ?></h3>
                    <p><?= htmlspecialchars($category['description']); ?></p>
                    <a href="add_workout_plan.php?category_id=<?= $category['id']; ?>">Add Workout Plan</a>
                </div>
            <?php endwhile; ?>
        </div>

        <form action="add_category.php" method="POST">
            <h3>Add New Category</h3>
            <input type="text" name="category_name" id="category_name" placeholder="Category Name" required>
            <textarea name="category_desc" id="category_desc" placeholder="Category Description" required></textarea>
            <button type="submit">Add Category</button>
        </form>
    </div>
</body>
</html>
