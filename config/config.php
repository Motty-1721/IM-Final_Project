<?php

// This is the hostname where our database lives
$hostname = "localhost";

// This is the username we use to login to the database
$username = "root";

// This is the password for the database (XAMPP default has no password)
$password = "";

// This is the name of our database
$database = "grilliance_db";

// Try to connect to the database
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection worked
if (!$conn) {
    // If connection failed, show error message and stop
    die("Connection failed: " . mysqli_connect_error());
}

?>
