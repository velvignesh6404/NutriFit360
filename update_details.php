<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

if (isset($_POST['submit'])) {
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $height = filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $weight = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $goal = filter_var($_POST['goal'], FILTER_SANITIZE_STRING);
    $food_preference = filter_var($_POST['food_preference'], FILTER_SANITIZE_STRING);

    $update_details = $conn->prepare("
    UPDATE `users` 
    SET age = ?, height = ?, weight = ?, goal = ?, food_preference = ? 
    WHERE id = ?
    ");
    $update_details->execute([$age, $height, $weight, $goal, $food_preference, $user_id]);

    if ($update_details->rowCount() > 0) {
        $message[] = 'User details updated successfully!';
    } else {
        $message[] = 'Failed to update user details. No changes made.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">
    <form action="" method="post">
        <h3>Update Your Details</h3>
        <input type="number" class="box" placeholder="Age (in years)" required min="1" max="120" name="age">
        <input type="number" step="0.01" class="box" placeholder="Height (in cm)" required min="50" max="250" name="height">
        <input type="number" step="0.01" class="box" placeholder="Weight (in kg)" required min="10" max="500" name="weight">
        <select class="box" name="goal" required>
            <option value="" disabled selected>Select your goal</option>
            <option value="weight_loss">Weight Loss</option>
            <option value="weight_gain">Weight Gain</option>
            <option value="maintain_weight">Maintain Weight</option>
        </select>
        <select class="box" name="food_preference" required>
            <option value="" disabled selected>Select food preference</option>
            <option value="veg">Vegetarian</option>
            <option value="non_veg">Non-Vegetarian</option>
        </select>
        <input type="submit" value="Save Details" name="submit" class="btn">
    </form>
</section>

<?php include 'components/footer.php'; ?>

</body>
</html>
