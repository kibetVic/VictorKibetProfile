<?php
// send_email.php - Complete working version

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON response header
header('Content-Type: application/json');

// Load PHPMailer
require_once 'wp-includes/PHPMailer/Exception.php';
require_once 'wp-includes/PHPMailer/PHPMailer.php';
require_once 'wp-includes/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method. Please use POST.'
    ]);
    exit();
}

// Get and validate form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validate inputs
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

// If there are validation errors, return them
if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'errors' => $errors
    ]);
    exit();
}

// Create PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kibetvictor0603@gmail.com';
    $mail->Password = 'ughk wiij qztd qgde'; // App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    $mail->SMTPDebug = 0; // Set to 2 for debugging

    // Recipients
    $mail->setFrom('kibetvictor0603@gmail.com', 'Victor Kibet Portfolio');
    $mail->addAddress('kibetvic98@gmail.com', 'Victor Kibet');
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(true);
    $mail->Subject = "Portfolio Contact: " . $subject;

    // Email Body HTML
    $mail->Body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .header { background: #00d4ff; color: #0f172a; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; margin: -30px -30px 20px -30px; }
            .field { margin: 15px 0; padding: 10px; border-bottom: 1px solid #eee; }
            .label { font-weight: bold; color: #666; display: inline-block; width: 120px; }
            .value { color: #333; }
            .reply-box { background: #f0f9ff; padding: 15px; border-left: 4px solid #00d4ff; margin: 20px 0; }
            .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #999; text-align: center; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>📧 New Contact Form Submission</h2>
            </div>

            <div class='reply-box'>
                <p><strong>📫 Reply to:</strong> <a href='mailto:{$email}'>{$email}</a></p>
                <p><strong>👤 From:</strong> {$name}</p>
            </div>

            <div class='field'>
                <span class='label'>Name:</span>
                <span class='value'>{$name}</span>
            </div>

            <div class='field'>
                <span class='label'>Email:</span>
                <span class='value'><a href='mailto:{$email}'>{$email}</a></span>
            </div>

            <div class='field'>
                <span class='label'>Subject:</span>
                <span class='value'>{$subject}</span>
            </div>

            <div class='field'>
                <span class='label'>Message:</span>
                <div class='value' style='margin-top:10px; padding:10px; background:#f8fafc; border-radius:5px;'>
                    " . nl2br(htmlspecialchars($message)) . "
                </div>
            </div>

            <div class='field'>
                <span class='label'>Submitted:</span>
                <span class='value'>" . date('l, F j, Y \a\t g:i A') . "</span>
            </div>

            <div class='footer'>
                <p>This message was sent from Victor Kibet's Portfolio Website</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Plain text version
    $mail->AltBody = "New Contact Form Submission\n\n" .
                     "Name: {$name}\n" .
                     "Email: {$email}\n" .
                     "Subject: {$subject}\n" .
                     "Message:\n{$message}\n\n" .
                     "Reply to: {$email}\n" .
                     "Sent: " . date('l, F j, Y \a\t g:i A');

    // Send email
    $mail->send();

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Your message has been sent successfully! I will get back to you soon.'
    ]);

} catch (Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'error' => 'Mailer Error: ' . $mail->ErrorInfo,
        'debug' => $e->getMessage() // Remove this in production
    ]);
}
?>