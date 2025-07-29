<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

$select_bookings = $conn->prepare("SELECT * FROM `table_bookings`");
$select_bookings->execute();
$booked_records = $select_bookings->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">

    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f5f5f5;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    h2 {
        font-size: 2em;
        color: #333;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        table-layout: fixed;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: left;
        word-wrap: break-word;
        white-space: normal;
    }

    table td {
        font-size: 1.3em;
        font-weight: medium;
    }

    table th {
        font-size: 1.3em;
        background-color: black;
        color: #fff;
    }

    table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .status-update {
        display: flex;
        align-items: center;
    }

    .status-update select[name="status"] {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 10px;
        width: 200px;
    }

    .status-update button[type="submit"] {
        padding: 10px 20px;
        font-size: 16px;
        background-color: orange;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .status-update button[type="submit"]:hover {
        background-color: black;
    }

    @media screen and (max-width: 600px) {
        .container {
            padding: 10px;
        }

        table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        table th,
        table td {
            display: block;
            text-align: left;
        }

        table thead {
            display: none;
        }

        table td {
            font-size: 1em;
        }
    }

    p.no-bookings-message {
        text-align: center;
        font-size: 18px;
        color: #555;
        margin-top: 20px;
    }
</style>


</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <section class="booked-calls">
        <div class="container">
            <h2>Booking Information</h2>

            <?php if (!empty($booked_records)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Booking Time</th>
                            <th>Booking Date</th>
                            <th>Status</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($booked_records as $record) : ?>
                            <tr>
                                <td><?= $record['id']; ?></td>
                                <td><?= $record['full_name']; ?></td>
                                <td><?= $record['email']; ?></td>
                                <td><?= $record['phone_number']; ?></td>
                                <td><?= $record['booking_time']; ?></td>
                                <td><?= $record['booking_date']; ?></td>
                                <td>
                                    <form method="post" action="update_booking_status.php">
                                        <input type="hidden" name="booking_id" value="<?= $record['id']; ?>">
                                        <div class="status-update">
                                            <select name="status">
                                                <option value="Pending" <?= ($record['booking_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Confirmed" <?= ($record['booking_status'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                                <option value="Cancelled" <?= ($record['booking_status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <button type="submit">Update</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p class="no-bookings-message">No bookings available.</p>
            <?php endif; ?>

        </div>
    </section>

</body>

</html>
