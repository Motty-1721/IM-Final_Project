<?php
// Email helper functions using PHPMailer

// Load PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/email-config.php';

/**
 * Send email when customer creates a new reservation
 */
function sendReservationCreatedEmail($customerEmail, $customerName, $reservationDate, $reservationTime, $numberOfGuests) {
    $mail = setupMailer();

    try {
        // Recipient
        $mail->addAddress($customerEmail, $customerName);

        // Email subject
        $mail->Subject = 'Reservation Request Received - Grilliance Restaurant';

        // Email body (HTML)
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                <h2 style='color: #d4a574;'>Reservation Request Received</h2>
                <p>Dear $customerName,</p>
                <p>Thank you for choosing Grilliance Restaurant! We have received your reservation request.</p>

                <div style='background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <h3 style='margin-top: 0;'>Reservation Details:</h3>
                    <p><strong>Date:</strong> " . date('F d, Y', strtotime($reservationDate)) . "</p>
                    <p><strong>Time:</strong> " . date('g:i A', strtotime($reservationTime)) . "</p>
                    <p><strong>Number of Guests:</strong> $numberOfGuests</p>
                    <p><strong>Status:</strong> <span style='color: #856404;'>Pending Approval</span></p>
                </div>

                <p>Your reservation is currently pending approval. Our team will review it shortly and you will receive another email once it's confirmed.</p>

                <p>If you have any questions, please don't hesitate to contact us.</p>

                <p>Best regards,<br>
                <strong>Grilliance Restaurant Team</strong></p>
            </div>
        </body>
        </html>
        ";

        // Plain text version
        $mail->AltBody = "Dear $customerName,\n\nThank you for your reservation request!\n\nReservation Details:\nDate: " . date('F d, Y', strtotime($reservationDate)) . "\nTime: " . date('g:i A', strtotime($reservationTime)) . "\nGuests: $numberOfGuests\nStatus: Pending Approval\n\nYou will receive another email once your reservation is confirmed.\n\nBest regards,\nGrilliance Restaurant Team";

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Send email when admin approves a reservation
 */
function sendReservationApprovedEmail($customerEmail, $customerName, $reservationDate, $reservationTime, $numberOfGuests) {
    $mail = setupMailer();

    try {
        // Recipient
        $mail->addAddress($customerEmail, $customerName);

        // Email subject
        $mail->Subject = 'Reservation Confirmed - Grilliance Restaurant';

        // Email body (HTML)
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                <h2 style='color: #28a745;'>✓ Reservation Confirmed!</h2>
                <p>Dear $customerName,</p>
                <p>Great news! Your reservation at Grilliance Restaurant has been <strong style='color: #28a745;'>CONFIRMED</strong>.</p>

                <div style='background-color: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745;'>
                    <h3 style='margin-top: 0;'>Confirmed Reservation Details:</h3>
                    <p><strong>Date:</strong> " . date('F d, Y', strtotime($reservationDate)) . "</p>
                    <p><strong>Time:</strong> " . date('g:i A', strtotime($reservationTime)) . "</p>
                    <p><strong>Number of Guests:</strong> $numberOfGuests</p>
                    <p><strong>Status:</strong> <span style='color: #155724;'>✓ Confirmed</span></p>
                </div>

                <p>We look forward to welcoming you! Please arrive 10 minutes before your reservation time.</p>

                <p><strong>Important:</strong> Confirmed reservations cannot be modified. If you need to cancel, please contact us directly.</p>

                <p>See you soon!<br>
                <strong>Grilliance Restaurant Team</strong></p>
            </div>
        </body>
        </html>
        ";

        // Plain text version
        $mail->AltBody = "Dear $customerName,\n\nYour reservation has been CONFIRMED!\n\nConfirmed Details:\nDate: " . date('F d, Y', strtotime($reservationDate)) . "\nTime: " . date('g:i A', strtotime($reservationTime)) . "\nGuests: $numberOfGuests\n\nWe look forward to welcoming you!\n\nBest regards,\nGrilliance Restaurant Team";

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Send email when admin cancels a reservation
 */
function sendReservationCancelledEmail($customerEmail, $customerName, $reservationDate, $reservationTime, $cancellationReason) {
    $mail = setupMailer();

    try {
        // Recipient
        $mail->addAddress($customerEmail, $customerName);

        // Email subject
        $mail->Subject = 'Reservation Cancelled - Grilliance Restaurant';

        // Email body (HTML)
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                <h2 style='color: #dc3545;'>Reservation Cancelled</h2>
                <p>Dear $customerName,</p>
                <p>We regret to inform you that your reservation at Grilliance Restaurant has been <strong style='color: #dc3545;'>CANCELLED</strong>.</p>

                <div style='background-color: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #dc3545;'>
                    <h3 style='margin-top: 0;'>Cancelled Reservation:</h3>
                    <p><strong>Date:</strong> " . date('F d, Y', strtotime($reservationDate)) . "</p>
                    <p><strong>Time:</strong> " . date('g:i A', strtotime($reservationTime)) . "</p>
                    <p><strong>Reason:</strong> $cancellationReason</p>
                </div>

                <p>We apologize for any inconvenience this may cause. You are welcome to make another reservation at your convenience.</p>

                <p>If you have any questions or concerns, please don't hesitate to contact us.</p>

                <p>Best regards,<br>
                <strong>Grilliance Restaurant Team</strong></p>
            </div>
        </body>
        </html>
        ";

        // Plain text version
        $mail->AltBody = "Dear $customerName,\n\nYour reservation has been CANCELLED.\n\nCancelled Reservation:\nDate: " . date('F d, Y', strtotime($reservationDate)) . "\nTime: " . date('g:i A', strtotime($reservationTime)) . "\nReason: $cancellationReason\n\nWe apologize for the inconvenience. You can make another reservation anytime.\n\nBest regards,\nGrilliance Restaurant Team";

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Setup PHPMailer with Gmail SMTP settings
 */
function setupMailer() {
    $mail = new PHPMailer(true);

    // Server settings
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port       = SMTP_PORT;
    $mail->CharSet    = SMTP_CHARSET;

    // Sender
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);

    // Enable HTML
    $mail->isHTML(true);

    return $mail;
}
?>
