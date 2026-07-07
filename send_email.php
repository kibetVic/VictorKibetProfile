<?php
// send_email.php - Complete working version with display messages

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load PHPMailer
require_once 'wp-includes/PHPMailer/Exception.php';
require_once 'wp-includes/PHPMailer/PHPMailer.php';
require_once 'wp-includes/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid request method. Please use POST.");
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

// If there are validation errors, display them
if (!empty($errors)) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Contact Form</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; padding: 20px; }
            .container { max-width: 500px; width: 100%; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
            .error-icon { font-size: 60px; color: #dc3545; margin-bottom: 20px; }
            h2 { color: #dc3545; margin-bottom: 20px; }
            .error-list { text-align: left; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #dc3545; }
            .error-list li { color: #721c24; margin: 5px 0; list-style: none; }
            .btn { display: inline-block; padding: 12px 30px; background: #00d4ff; color: #0f172a; text-decoration: none; border-radius: 30px; font-weight: 600; margin-top: 20px; border: none; cursor: pointer; }
            .btn:hover { background: #00b8d4; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='error-icon'>❌</div>
            <h2>Form Validation Error</h2>
            <div class='error-list'>
                <ul>
    ";
    foreach ($errors as $error) {
        echo "<li>• " . htmlspecialchars($error) . "</li>";
    }
    echo "
                </ul>
            </div>
            <a href='index.html#contact' class='btn'>Go Back to Form</a>
        </div>
    </body>
    </html>";
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

    // Display success message
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Success - Contact Form</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; padding: 20px; }
            .container { max-width: 500px; width: 100%; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
            .success-icon { font-size: 60px; color: #28a745; margin-bottom: 20px; }
            h2 { color: #28a745; margin-bottom: 10px; }
            p { color: #555; margin: 20px 0; line-height: 1.6; }
            .btn { display: inline-block; padding: 12px 30px; background: #00d4ff; color: #0f172a; text-decoration: none; border-radius: 30px; font-weight: 600; margin-top: 20px; border: none; cursor: pointer; }
            .btn:hover { background: #00b8d4; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='success-icon'>✅</div>
            <h2>Message Sent Successfully!</h2>
            <p>Thank you <strong>" . htmlspecialchars($name) . "</strong> for reaching out.<br>
            I will get back to you at <strong>" . htmlspecialchars($email) . "</strong> as soon as possible.</p>
            <a href='index.html#contact' class='btn'>Send Another Message</a>
        </div>
    </body>
    </html>";

} catch (Exception $e) {
    // Display detailed error message
    $errorMessage = $mail->ErrorInfo;
    
    // Check for specific errors and provide user-friendly messages
    if (strpos($errorMessage, 'Username and Password not accepted') !== false) {
        $errorMessage = 'Gmail authentication failed. Please check your email credentials and ensure you are using an App Password.';
    } elseif (strpos($errorMessage, 'Connection refused') !== false) {
        $errorMessage = 'Could not connect to SMTP server. Please check your internet connection and SMTP settings.';
    } elseif (strpos($errorMessage, 'Network is unreachable') !== false) {
        $errorMessage = 'Network error. Please check your internet connection.';
    } elseif (strpos($errorMessage, 'Could not authenticate') !== false) {
        $errorMessage = 'Authentication failed. Please verify your Gmail App Password.';
    }
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Contact Form</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; padding: 20px; }
            .container { max-width: 500px; width: 100%; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
            .error-icon { font-size: 60px; color: #dc3545; margin-bottom: 20px; }
            h2 { color: #dc3545; margin-bottom: 10px; }
            .error-details { background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0; text-align: left; border-left: 4px solid #dc3545; }
            .error-details p { color: #721c24; margin: 5px 0; word-wrap: break-word; }
            .btn { display: inline-block; padding: 12px 30px; background: #00d4ff; color: #0f172a; text-decoration: none; border-radius: 30px; font-weight: 600; margin-top: 20px; border: none; cursor: pointer; }
            .btn:hover { background: #00b8d4; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='error-icon'>❌</div>
            <h2>Failed to Send Message</h2>
            <div class='error-details'>
                <p><strong>Error:</strong> " . htmlspecialchars($errorMessage) . "</p>
                " . (isset($e->getMessage()) ? "<p><strong>Debug:</strong> " . htmlspecialchars($e->getMessage()) . "</p>" : "") . "
            </div>
            <a href='index.html#contact' class='btn'>Try Again</a>
        </div>
    </body>
    </html>";
}
?>