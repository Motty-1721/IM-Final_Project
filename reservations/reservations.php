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

// Get the logged in user's ID
$user_id = $_SESSION['user_id'];

// Get all reservations for this user from the database
$sql = "SELECT * FROM reservations WHERE user_id = '$user_id' ORDER BY reservation_date DESC, reservation_time DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - Grilliance</title>
    <link rel="stylesheet" href="reservations-style.css">
</head>
<body>
    <div class="reservations-container">
        <h1 class="page-title">My Reservations</h1>

        <a href="new-reservation.php" class="btn-new-reservation">+ New Reservation</a>

        <?php if (isset($_GET['success'])): ?>
            <div class="message success">
                <?php
                if ($_GET['success'] == 'created') echo "Reservation created successfully!";
                if ($_GET['success'] == 'updated') echo "Reservation updated successfully!";
                if ($_GET['success'] == 'deleted') echo "Reservation deleted successfully!";
                ?>
            </div>
        <?php endif; ?>

        <div class="reservations-table">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <th>Special Requests</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($reservation = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($reservation['reservation_date'])); ?></td>
                                <td><?php echo date('g:i A', strtotime($reservation['reservation_time'])); ?></td>
                                <td><?php echo $reservation['number_of_guests']; ?> people</td>
                                <td>
                                    <span class="status-badge status-<?php echo $reservation['status']; ?>">
                                        <?php echo ucfirst($reservation['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $reservation['special_requests'] ? $reservation['special_requests'] : 'None'; ?></td>
                                <td>
                                    <a href="edit-reservation.php?id=<?php echo $reservation['id']; ?>" class="btn-action btn-edit">Edit</a>
                                    <a href="delete-reservation.php?id=<?php echo $reservation['id']; ?>"
                                       class="btn-action btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No Reservations Yet</h3>
                    <p>You haven't made any reservations. Click the button above to make your first reservation!</p>
                </div>
            <?php endif; ?>
        </div>

        <a href="../index.php" class="back-link">← Back to Home</a>
    </div>
</body>
</html>
