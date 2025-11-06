<?php
// Include database connection
include '../config/config.php';

// This variable will hold error or success messages
$message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the data from the form
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password != $confirm_password) {
        $message = "Passwords do not match!";
    } else {

        // Check if email already exists in database
        $check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($result) > 0) {
            $message = "This email is already registered!";
        } else {

            // Hash the password to keep it secret and safe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $sql = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$hashed_password')";

            if (mysqli_query($conn, $sql)) {
                $message = "Registration successful! You can now login.";
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Grilliance</title>
    <link rel="stylesheet" href="auth-style.css">
    <script src="auth-validation.js" defer></script>
</head>
<body>
    <div class="auth-container">
        <h2 class="auth-title">Create Account</h2>

        <?php if ($message != ""): ?>
            <div class="message <?php echo (strpos($message, 'successful') !== false) ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" id="registerForm">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-submit">Sign Up</button>
        </form>

        <div class="auth-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
