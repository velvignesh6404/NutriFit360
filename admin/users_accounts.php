<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_users->execute([$delete_id]);
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
    $delete_order->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$delete_id]);
    header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Accounts</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">   
    <style>
.dashboard-table {
    max-width: 800px;
    margin: 0 auto;
    border-collapse: collapse;
    background-color: #fff;
}

.dashboard-table th,
.dashboard-table td {
    padding: 10px;
    border: 1px solid #ddd;
}

.dashboard-table th {
    font-weight: bold;
    font-size: 1.8em;
    background-color: black;
    color: white;
}

.dashboard-table td {
    font-size: 1.5em; 
}

.dashboard-table .empty {
    text-align: center;
    font-style: italic; 
}

.dashboard-table .delete-btn {
    display: inline-block;
    text-decoration: none;
    color: #fff;
    background-color: #e74c3c;
    padding: 3px 8px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.dashboard-table .delete-btn:hover {
    background-color: darkred;
}
    </style>
    
</head>

<body>

    <?php include '../components/admin_header.php' ?>

    <section class="accounts">

        <h1 class="heading">Users Accounts</h1>

        <div class="table-container">

            <table class="dashboard-table">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Remove</th>
                </tr>

                <?php
                $select_account = $conn->prepare("SELECT * FROM `users`");
                $select_account->execute();
                if ($select_account->rowCount() > 0) {
                    while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <tr>
                            <td><?= $fetch_accounts['id']; ?></td>
                            <td><?= $fetch_accounts['name']; ?></td>
                            <td><a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">Remove</a></td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="3" class="empty">No accounts available</td></tr>';
                }
                ?>
            </table>
        </div>
    </section>

    <script src="../js/admin_script.js"></script>

</body>

</html>
