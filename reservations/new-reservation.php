<?php
// Start session to check if user is logged in
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, send them to login page
    header("Location: ../auth/login.php");
    exit();
}

// Include database connection
include '../config/config.php';

// Include email functions
include '../config/email-functions.php';

// This variable will hold error or success messages
$message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the data from the form
    $user_id = $_SESSION['user_id'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];
    $number_of_guests = $_POST['number_of_guests'];
    $special_requests = $_POST['special_requests'];

    // Insert the new reservation into the database
    $sql = "INSERT INTO reservations (user_id, reservation_date, reservation_time, number_of_guests, special_requests, status)
            VALUES ('$user_id', '$reservation_date', '$reservation_time', '$number_of_guests', '$special_requests', 'pending')";

    if (mysqli_query($conn, $sql)) {
        // Get customer email and name to send confirmation email
        $user_query = "SELECT email, full_name FROM users WHERE id = '$user_id'";
        $user_result = mysqli_query($conn, $user_query);
        $user_data = mysqli_fetch_assoc($user_result);

        // Send email notification to customer
        sendReservationCreatedEmail(
            $user_data['email'],
            $user_data['full_name'],
            $reservation_date,
            $reservation_time,
            $number_of_guests
        );

        // Redirect back to reservations page with success message
        header("Location: reservations.php?success=created");
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reservation - Grilliance</title>
    <link rel="stylesheet" href="reservations-style.css">
    <script src="reservation-validation.js" defer></script>
</head>
<body>
    <div class="reservations-container">
        <h1 class="page-title">Make a Reservation</h1>

        <?php if ($message != ""): ?>
            <div class="message error">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="reservation-form">
            <form method="POST" action="new-reservation.php">
                <div class="form-group">
                    <label for="reservation_date">Reservation Date</label>
                    <input type="date" id="reservation_date" name="reservation_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="reservation_time">Reservation Time</label>
                    <select id="reservation_time" name="reservation_time" required>
                        <option value="">Select a time</option>
                        <option value="11:00:00">11:00 AM</option>
                        <option value="11:30:00">11:30 AM</option>
                        <option value="12:00:00">12:00 PM</option>
                        <option value="12:30:00">12:30 PM</option>
                        <option value="13:00:00">1:00 PM</option>
                        <option value="13:30:00">1:30 PM</option>
                        <option value="14:00:00">2:00 PM</option>
                        <option value="17:00:00">5:00 PM</option>
                        <option value="17:30:00">5:30 PM</option>
                        <option value="18:00:00">6:00 PM</option>
                        <option value="18:30:00">6:30 PM</option>
                        <option value="19:00:00">7:00 PM</option>
                        <option value="19:30:00">7:30 PM</option>
                        <option value="20:00:00">8:00 PM</option>
                        <option value="20:30:00">8:30 PM</option>
                        <option value="21:00:00">9:00 PM</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="number_of_guests">Number of Guests</label>
                    <select id="number_of_guests" name="number_of_guests" required>
                        <option value="">Select number of guests</option>
                        <option value="1">1 Guest</option>
                        <option value="2">2 Guests</option>
                        <option value="3">3 Guests</option>
                        <option value="4">4 Guests</option>
                        <option value="5">5 Guests</option>
                        <option value="6">6 Guests</option>
                        <option value="7">7 Guests</option>
                        <option value="8">8 Guests</option>
                        <option value="9">9 Guests</option>
                        <option value="10">10+ Guests</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="special_requests">Special Requests (Optional)</label>
                    <textarea id="special_requests" name="special_requests" placeholder="Any dietary restrictions, special occasions, or seating preferences..."></textarea>
                </div>

                <button type="submit" class="btn-submit">Submit Reservation</button>
            </form>

            <a href="reservations.php" class="back-link">← Cancel and go back</a>
        </div>
    </div>
</body>
</html>
