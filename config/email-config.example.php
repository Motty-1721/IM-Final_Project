<?php
// Email configuration settings for PHPMailer

// Gmail SMTP settings
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');

// Your Gmail credentials
// IMPORTANT: Use App Password, not your regular Gmail password!
// See EMAIL_SETUP_GUIDE.txt for instructions on creating an App Password
define('SMTP_USERNAME', 'your-email@gmail.com');  // Replace with your Gmail address
define('SMTP_PASSWORD', 'your-app-password-here'); // Replace with 16-character App Password

// Sender information (appears in "From" field)
define('SMTP_FROM_EMAIL', 'your-email@gmail.com'); // Same as SMTP_USERNAME
define('SMTP_FROM_NAME', 'Grilliance Restaurant'); // Restaurant name

// Email settings
define('SMTP_CHARSET', 'UTF-8');

// SECURITY NOTE:
// Never share this file or upload it to public repositories!
// This file contains sensitive password information.
?>
