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

// Get the reservation ID from the URL
$reservation_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Get the reservation details from database
$sql = "SELECT * FROM reservations WHERE id = '$reservation_id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

// Check if reservation exists and belongs to this user
if (mysqli_num_rows($result) == 0) {
    // If not found or doesn't belong to user, redirect back
    header("Location: reservations.php");
    exit();
}

// Get the reservation data
$reservation = mysqli_fetch_assoc($result);

// Check if reservation is confirmed or cancelled (they cannot be edited)
if ($reservation['status'] == 'confirmed' || $reservation['status'] == 'cancelled') {
    // Redirect back - confirmed and cancelled reservations cannot be edited
    header("Location: reservations.php");
    exit();
}

// This variable will hold error or success messages
$message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the updated data from the form
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];
    $number_of_guests = $_POST['number_of_guests'];
    $special_requests = $_POST['special_requests'];

    // Update the reservation in the database (customers cannot change status)
    $sql = "UPDATE reservations SET
            reservation_date = '$reservation_date',
            reservation_time = '$reservation_time',
            number_of_guests = '$number_of_guests',
            special_requests = '$special_requests'
            WHERE id = '$reservation_id' AND user_id = '$user_id'";

    if (mysqli_query($conn, $sql)) {
        // Redirect back to reservations page with success message
        header("Location: reservations.php?success=updated");
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
    <title>Edit Reservation - Grilliance</title>
    <link rel="stylesheet" href="reservations-style.css">
    <script src="reservation-validation.js" defer></script>
</head>
<body>
    <div class="reservations-container">
        <h1 class="page-title">Edit Reservation</h1>

        <?php if ($message != ""): ?>
            <div class="message error">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="reservation-form">
            <form method="POST" action="edit-reservation.php?id=<?php echo $reservation_id; ?>">
                <div class="form-group">
                    <label for="reservation_date">Reservation Date</label>
                    <input type="date" id="reservation_date" name="reservation_date"
                           value="<?php echo $reservation['reservation_date']; ?>"
                           required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="reservation_time">Reservation Time</label>
                    <select id="reservation_time" name="reservation_time" required>
                        <option value="">Select a time</option>
                        <option value="11:00:00" <?php if($reservation['reservation_time'] == '11:00:00') echo 'selected'; ?>>11:00 AM</option>
                        <option value="11:30:00" <?php if($reservation['reservation_time'] == '11:30:00') echo 'selected'; ?>>11:30 AM</option>
                        <option value="12:00:00" <?php if($reservation['reservation_time'] == '12:00:00') echo 'selected'; ?>>12:00 PM</option>
                        <option value="12:30:00" <?php if($reservation['reservation_time'] == '12:30:00') echo 'selected'; ?>>12:30 PM</option>
                        <option value="13:00:00" <?php if($reservation['reservation_time'] == '13:00:00') echo 'selected'; ?>>1:00 PM</option>
                        <option value="13:30:00" <?php if($reservation['reservation_time'] == '13:30:00') echo 'selected'; ?>>1:30 PM</option>
                        <option value="14:00:00" <?php if($reservation['reservation_time'] == '14:00:00') echo 'selected'; ?>>2:00 PM</option>
                        <option value="17:00:00" <?php if($reservation['reservation_time'] == '17:00:00') echo 'selected'; ?>>5:00 PM</option>
                        <option value="17:30:00" <?php if($reservation['reservation_time'] == '17:30:00') echo 'selected'; ?>>5:30 PM</option>
                        <option value="18:00:00" <?php if($reservation['reservation_time'] == '18:00:00') echo 'selected'; ?>>6:00 PM</option>
                        <option value="18:30:00" <?php if($reservation['reservation_time'] == '18:30:00') echo 'selected'; ?>>6:30 PM</option>
                        <option value="19:00:00" <?php if($reservation['reservation_time'] == '19:00:00') echo 'selected'; ?>>7:00 PM</option>
                        <option value="19:30:00" <?php if($reservation['reservation_time'] == '19:30:00') echo 'selected'; ?>>7:30 PM</option>
                        <option value="20:00:00" <?php if($reservation['reservation_time'] == '20:00:00') echo 'selected'; ?>>8:00 PM</option>
                        <option value="20:30:00" <?php if($reservation['reservation_time'] == '20:30:00') echo 'selected'; ?>>8:30 PM</option>
                        <option value="21:00:00" <?php if($reservation['reservation_time'] == '21:00:00') echo 'selected'; ?>>9:00 PM</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="number_of_guests">Number of Guests</label>
                    <select id="number_of_guests" name="number_of_guests" required>
                        <option value="">Select number of guests</option>
                        <option value="1" <?php if($reservation['number_of_guests'] == 1) echo 'selected'; ?>>1 Guest</option>
                        <option value="2" <?php if($reservation['number_of_guests'] == 2) echo 'selected'; ?>>2 Guests</option>
                        <option value="3" <?php if($reservation['number_of_guests'] == 3) echo 'selected'; ?>>3 Guests</option>
                        <option value="4" <?php if($reservation['number_of_guests'] == 4) echo 'selected'; ?>>4 Guests</option>
                        <option value="5" <?php if($reservation['number_of_guests'] == 5) echo 'selected'; ?>>5 Guests</option>
                        <option value="6" <?php if($reservation['number_of_guests'] == 6) echo 'selected'; ?>>6 Guests</option>
                        <option value="7" <?php if($reservation['number_of_guests'] == 7) echo 'selected'; ?>>7 Guests</option>
                        <option value="8" <?php if($reservation['number_of_guests'] == 8) echo 'selected'; ?>>8 Guests</option>
                        <option value="9" <?php if($reservation['number_of_guests'] == 9) echo 'selected'; ?>>9 Guests</option>
                        <option value="10" <?php if($reservation['number_of_guests'] == 10) echo 'selected'; ?>>10+ Guests</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="special_requests">Special Requests (Optional)</label>
                    <textarea id="special_requests" name="special_requests" placeholder="Any dietary restrictions, special occasions, or seating preferences..."><?php echo $reservation['special_requests']; ?></textarea>
                </div>

                <button type="submit" class="btn-submit">Update Reservation</button>
            </form>

            <a href="reservations.php" class="back-link">← Cancel and go back</a>
        </div>
    </div>
</body>
</html>
