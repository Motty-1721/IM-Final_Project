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

// First check if the reservation is confirmed (confirmed reservations cannot be deleted)
$check_sql = "SELECT status FROM reservations WHERE id = '$reservation_id' AND user_id = '$user_id'";
$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    $reservation = mysqli_fetch_assoc($check_result);

    // If confirmed, redirect back without deleting
    if ($reservation['status'] == 'confirmed') {
        header("Location: reservations.php");
        exit();
    }
}

// Delete the reservation from the database
// Only delete if it belongs to the logged in user and is not confirmed
$sql = "DELETE FROM reservations WHERE id = '$reservation_id' AND user_id = '$user_id'";

if (mysqli_query($conn, $sql)) {
    // Redirect back to reservations page with success message
    header("Location: reservations.php?success=deleted");
    exit();
} else {
    // If there was an error, redirect back with error
    header("Location: reservations.php?error=delete_failed");
    exit();
}
?>
