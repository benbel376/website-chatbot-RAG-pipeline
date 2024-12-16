<?php
// send_email.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate inputs
    $fullname = trim(filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));

    // Check for empty fields or invalid email
    if (!$fullname || !$email || !$message) {
        $error_message = "Please ensure all fields are filled out correctly.";
        header("Location: contact.php?status=error&message=" . urlencode($error_message));
        exit;
    }

    // Email configuration
    $to = "admin@biniyambelayneh.com"; // Replace with your actual email address
    $subject = "New Contact Form Submission from {$fullname}";
    $headers = "From: {$email}\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "You have received a new message from your website contact form.\n\n";
    $body .= "Name: {$fullname}\n";
    $body .= "Email: {$email}\n\n";
    $body .= "Message:\n{$message}\n";

    // Attempt to send the email
    if (mail($to, $subject, $body, $headers)) {
        $success_message = "Thank you, your message has been sent successfully.";
        header("Location: contact.php?status=success&message=" . urlencode($success_message));
    } else {
        $error_message = "Sorry, something went wrong. Please try again later.";
        header("Location: contact.php?status=error&message=" . urlencode($error_message));
    }
} else {
    // Redirect to contact page if accessed directly
    header("Location: contact.php");
    exit;
}
?>
