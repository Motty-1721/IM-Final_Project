<?php
// Start session to check who is logged in
session_start();

// Include database connection
include '../config/config.php';

// Include email functions
include '../config/email-functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Not logged in, send them to login page
    header("Location: ../auth/login.php");
    exit();
}

// Check if user is an admin
if ($_SESSION['role'] != 'admin') {
    // Not an admin, send them back to home page
    header("Location: ../index.php");
    exit();
}

// Get all reservations from ALL users
$sql = "SELECT reservations.*, users.full_name, users.email
        FROM reservations
        JOIN users ON reservations.user_id = users.id
        ORDER BY reservations.reservation_date DESC, reservations.reservation_time DESC";
$result = mysqli_query($conn, $sql);

// Handle approve action
if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'approve') {
    $reservation_id = $_GET['id'];

    // Get reservation and customer details before updating
    $details_query = "SELECT reservations.*, users.full_name, users.email
                      FROM reservations
                      JOIN users ON reservations.user_id = users.id
                      WHERE reservations.id = $reservation_id";
    $details_result = mysqli_query($conn, $details_query);
    $reservation_data = mysqli_fetch_assoc($details_result);

    // Change status to confirmed
    $update_sql = "UPDATE reservations SET status = 'confirmed' WHERE id = $reservation_id";
    mysqli_query($conn, $update_sql);

    // Send approval email to customer
    sendReservationApprovedEmail(
        $reservation_data['email'],
        $reservation_data['full_name'],
        $reservation_data['reservation_date'],
        $reservation_data['reservation_time'],
        $reservation_data['number_of_guests']
    );

    header("Location: dashboard.php");
    exit();
}

// Handle cancel action with reason
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    $cancellation_reason = mysqli_real_escape_string($conn, $_POST['cancellation_reason']);

    // Get reservation and customer details before updating
    $details_query = "SELECT reservations.*, users.full_name, users.email
                      FROM reservations
                      JOIN users ON reservations.user_id = users.id
                      WHERE reservations.id = $reservation_id";
    $details_result = mysqli_query($conn, $details_query);
    $reservation_data = mysqli_fetch_assoc($details_result);

    // Change status to cancelled and save reason
    $update_sql = "UPDATE reservations SET status = 'cancelled', cancellation_reason = '$cancellation_reason' WHERE id = $reservation_id";
    mysqli_query($conn, $update_sql);

    // Send cancellation email to customer
    sendReservationCancelledEmail(
        $reservation_data['email'],
        $reservation_data['full_name'],
        $reservation_data['reservation_date'],
        $reservation_data['reservation_time'],
        $cancellation_reason
    );

    header("Location: dashboard.php");
    exit();
}

// Handle delete action
if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'delete') {
    $reservation_id = $_GET['id'];

    // Delete the reservation
    $delete_sql = "DELETE FROM reservations WHERE id = $reservation_id";
    mysqli_query($conn, $delete_sql);
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Grilliance</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <div class="header-actions">
                <a href="../index.php" class="btn-secondary">Back to Home</a>
                <a href="../auth/logout.php" class="btn-logout">Logout</a>
            </div>
        </div>

        <div class="stats-container">
            <?php
            // Count reservations by status
            $pending_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM reservations WHERE status = 'pending'"));
            $confirmed_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM reservations WHERE status = 'confirmed'"));
            $cancelled_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM reservations WHERE status = 'cancelled'"));
            ?>
            <div class="stat-card">
                <h3>Pending</h3>
                <p class="stat-number"><?php echo $pending_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Confirmed</h3>
                <p class="stat-number"><?php echo $confirmed_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Cancelled</h3>
                <p class="stat-number"><?php echo $cancelled_count; ?></p>
            </div>
        </div>

        <div class="reservations-section">
            <h2>All Reservations</h2>

            <!-- Search and Filter Section -->
            <div class="search-filter-container">
                <input type="text" id="searchInput" placeholder="Search by customer name, email, or date..." class="search-box">

                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="pending">Pending</button>
                    <button class="filter-btn" data-filter="confirmed">Confirmed</button>
                    <button class="filter-btn" data-filter="cancelled">Cancelled</button>
                </div>
            </div>

            <?php if (mysqli_num_rows($result) == 0): ?>
                <p class="no-reservations">No reservations found.</p>
            <?php else: ?>
                <div class="table-container">
                    <table class="reservations-table" id="reservationsTable">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Guests</th>
                                <th>Special Requests</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reservationsBody">
                            <?php while ($reservation = mysqli_fetch_assoc($result)): ?>
                                <tr data-status="<?php echo $reservation['status']; ?>">
                                    <td data-label="Customer"><?php echo $reservation['full_name']; ?></td>
                                    <td data-label="Email"><?php echo $reservation['email']; ?></td>
                                    <td data-label="Date"><?php echo date('M d, Y', strtotime($reservation['reservation_date'])); ?></td>
                                    <td data-label="Time"><?php echo date('g:i A', strtotime($reservation['reservation_time'])); ?></td>
                                    <td data-label="Guests"><?php echo $reservation['number_of_guests']; ?> people</td>
                                    <td data-label="Special Requests"><?php echo $reservation['special_requests'] ? $reservation['special_requests'] : '-'; ?></td>
                                    <td data-label="Status">
                                        <span class="status-badge status-<?php echo $reservation['status']; ?>">
                                            <?php echo ucfirst($reservation['status']); ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-buttons">
                                            <?php if ($reservation['status'] == 'pending'): ?>
                                                <a href="dashboard.php?action=approve&id=<?php echo $reservation['id']; ?>" class="btn-approve">Approve</a>
                                                <button onclick="showCancelModal(<?php echo $reservation['id']; ?>, '<?php echo addslashes($reservation['full_name']); ?>')" class="btn-cancel">Cancel</button>
                                            <?php elseif ($reservation['status'] == 'confirmed'): ?>
                                                <button onclick="showCancelModal(<?php echo $reservation['id']; ?>, '<?php echo addslashes($reservation['full_name']); ?>')" class="btn-cancel">Cancel</button>
                                            <?php endif; ?>
                                            <a href="dashboard.php?action=delete&id=<?php echo $reservation['id']; ?>"
                                               class="btn-delete"
                                               onclick="return confirm('Are you sure you want to delete this reservation? This action cannot be undone.')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Cancellation Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCancelModal()">&times;</span>
            <h2>Cancel Reservation</h2>
            <p>Cancelling reservation for: <strong id="customerName"></strong></p>

            <form method="POST" action="dashboard.php">
                <input type="hidden" name="reservation_id" id="reservationId">
                <input type="hidden" name="cancel_reservation" value="1">

                <div class="form-group">
                    <label for="cancellation_reason">Reason for cancellation:</label>
                    <textarea id="cancellation_reason" name="cancellation_reason"
                              placeholder="Enter the reason for cancelling this reservation..."
                              required></textarea>
                </div>

                <div class="modal-buttons">
                    <button type="button" onclick="closeCancelModal()" class="btn-secondary">Back</button>
                    <button type="submit" class="btn-cancel">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show cancellation modal
        function showCancelModal(reservationId, customerName) {
            document.getElementById('cancelModal').style.display = 'block';
            document.getElementById('reservationId').value = reservationId;
            document.getElementById('customerName').textContent = customerName;
            document.getElementById('cancellation_reason').value = '';
        }

        // Close cancellation modal
        function closeCancelModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('cancelModal');
            if (event.target == modal) {
                closeCancelModal();
            }
        }

        // Search and Filter Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const tableBody = document.getElementById('reservationsBody');
            let currentFilter = 'all';

            // Add search functionality
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    filterReservations();
                });
            }

            // Add click handlers to filter buttons
            filterButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Get the filter value from the button
                    currentFilter = this.getAttribute('data-filter');

                    // Filter the reservations
                    filterReservations();
                });
            });

            // Main function to filter reservations
            function filterReservations() {
                const searchText = searchInput ? searchInput.value.toLowerCase() : '';
                const rows = tableBody.getElementsByTagName('tr');
                let visibleCount = 0;

                // Loop through each reservation row
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const status = row.getAttribute('data-status');
                    const rowText = row.textContent.toLowerCase();

                    // Check if row matches the status filter
                    const matchesFilter = currentFilter === 'all' || status === currentFilter;

                    // Check if row matches the search text
                    const matchesSearch = searchText === '' || rowText.includes(searchText);

                    // Show row only if it matches both filter and search
                    if (matchesFilter && matchesSearch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }

                // Show message if no results found
                showNoResultsMessage(visibleCount);
            }

            // Function to show message when no results are found
            function showNoResultsMessage(visibleCount) {
                const existingMessage = document.querySelector('.no-results-message');
                if (existingMessage) {
                    existingMessage.remove();
                }

                if (visibleCount === 0) {
                    const message = document.createElement('div');
                    message.className = 'no-results-message';
                    message.innerHTML = '<p>No reservations found matching your search.</p>';
                    message.style.textAlign = 'center';
                    message.style.padding = '30px';
                    message.style.color = '#999';

                    const table = document.getElementById('reservationsTable');
                    table.parentNode.insertBefore(message, table.nextSibling);
                }
            }
        });
    </script>
</body>
</html>
