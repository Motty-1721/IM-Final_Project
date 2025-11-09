<?php
// Start a session to remember who is logged in
session_start();

// Include database connection
include '../config/config.php';

// This variable will hold error or success messages
$message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the data from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Look for the user in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    // Check if we found a user with that email
    if (mysqli_num_rows($result) == 1) {

        // Get the user's information
        $user = mysqli_fetch_assoc($result);

        // Check if the password is correct
        if (password_verify($password, $user['password'])) {

            // Password is correct! Save user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Save user role (customer or admin)

            // Redirect to home page
            header("Location: ../index.php");
            exit();
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "No account found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Grilliance</title>
    <link rel="stylesheet" href="auth-style.css">
    <script src="auth-validation.js" defer></script>
</head>
<body>
    <div class="auth-container">
        <h2 class="auth-title">Welcome Back</h2>

        <?php if ($message != ""): ?>
            <div class="message error">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <div class="auth-link">
            Don't have an account? <a href="register.php">Sign up here</a>
        </div>
    </div>
</body>
</html>
