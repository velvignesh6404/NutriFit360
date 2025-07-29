<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = isset($_POST["full_name"]) ? $_POST["full_name"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $numPeople = isset($_POST["num_people"]) ? $_POST["num_people"] : 0;
    $bookingTime = isset($_POST["booking_time"]) ? $_POST["booking_time"] : '00:00:00';
    $bookingDate = isset($_POST["booking_date"]) ? $_POST["booking_date"] : '0000-00-00';
    $phoneNumber = isset($_POST["phone_number"]) ? $_POST["phone_number"] : '';
    $bookingStatus = 'Pending';

    $time = strtotime($bookingTime);
    if ($time < strtotime("09:00:00") || $time > strtotime("18:00:00")) {
        echo "Booking time must be between 9 AM and 6 PM.";
        exit;
    }

    // Insert booking into the database
    $insert_booking = $conn->prepare("INSERT INTO `table_bookings` (user_id, full_name, email, num_people, booking_time, booking_date, phone_number, booking_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($insert_booking->execute([$user_id, $fullName, $email, $numPeople, $bookingTime, $bookingDate, $phoneNumber, $bookingStatus, $consultationType])) {
        header('Location: booking.php?booking_success=true');
        exit();
    } else {
        echo "Error: " . implode(", ", $insert_booking->errorInfo());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Table</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/booking.css">
</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <div class="container">
        <?php
        if (isset($_GET['booking_success']) && $_GET['booking_success'] == 'true') {
            echo '<div class="confirmation-container">
                      <span class="close-btn" onclick="this.parentElement.style.display=\'none\'">&times;</span>
                      <p>Your booking has been made successfully. Please wait for confirmation.</p>
                  </div>';
        }
        ?>
    </div>

    <section class="booking" id="booking">
        <div class="title">
            <p>Schedule A Call</p>
        </div>
        <p class="sub-title">Book Your Doctor Appointment Now!</p>

        <form name="bookingForm" method="post" action="" onsubmit="return validateForm()">
            <div class="input">
                <p>Your full name?</p>
                <input type="text" name="full_name" placeholder="Write your name here..." required>
            </div>
            <div class="input">
                <p>Your email address?</p>
                <input type="email" name="email" placeholder="Write your email here..." required>
            </div>

            <div class="input">
                <p>How many people?</p>
                <select name="num_people" required>
                    <option value="1">1 person</option>
                    <option value="2">2 people</option>
                    <option value="3">3 people</option>
                    <option value="4">4 people</option>
                    <option value="5">5 people</option>
                    <option value="6">6 people</option>
                </select>
            </div>

            <div class="input">
                <p>What time?</p>
                <input type="time" name="booking_time" placeholder="10:00 AM" required>
            </div>

            <div class="input">
                <p>What is the date?</p>
                <input type="date" name="booking_date" required>
            </div>

            <div class="input">
                <p>Your Phone number?</p>
                <input type="tel" name="phone_number" placeholder="Write your number here..." required>
            </div>

            <button type="submit" class="btn">SUBMIT</button>
        </form>

        <p class="view-table-text">Already booked? <a class="view-table-link" href="user_booked_tables.php">View Scheduled Request</a></p>
    </section>

    <script>
        function validateForm() {
            var fullName = document.forms["bookingForm"]["full_name"].value;
            var email = document.forms["bookingForm"]["email"].value;
            var numPeople = document.forms["bookingForm"]["num_people"].value;
            var bookingTime = document.forms["bookingForm"]["booking_time"].value;
            var bookingDate = document.forms["bookingForm"]["booking_date"].value;
            var phoneNumber = document.forms["bookingForm"]["phone_number"].value;

            if (fullName == "") {
                alert("Please enter your full name.");
                return false;
            }

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email == "" || !emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            if (numPeople == "") {
                alert("Please select the number of people for table booking.");
                return false;
            }

            if (bookingTime == "") {
                alert("Please enter the booking time.");
                return false;
            }

            var time = new Date("1970-01-01T" + bookingTime + "Z");
            var startTime = new Date("1970-01-01T09:00:00Z");
            var endTime = new Date("1970-01-01T18:00:00Z");

            if (time < startTime || time > endTime) {
                alert("Booking time must be between 9 AM and 6 PM.");
                return false;
            }

            if (bookingDate == "") {
                alert("Please enter the booking date.");
                return false;
            }

            var today = new Date();
            var selectedDate = new Date(bookingDate);
            if (selectedDate < today) {
                alert("You cannot book for a past date.");
                return false;
            }

            var phoneRegex = /^\d{10}$/;
            if (phoneNumber == "" || !phoneRegex.test(phoneNumber)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }

            return true;
        }
    </script>

    <script src="js/script.js"></script>
</body>

</html>
