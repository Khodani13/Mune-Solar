<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include the Composer autoloader

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Log received POST values
    error_log(print_r($_POST, true));

    // Collect and sanitize POST data
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : 'Contact Form Submission'; // Default subject
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    // Capture Type of Use and Power Requirement
    $type_of_use = isset($_POST['type_of_use']) ? htmlspecialchars(trim($_POST['type_of_use'])) : 'Not Specified';
    $power_requirement = isset($_POST['power_requirement']) ? htmlspecialchars(trim($_POST['power_requirement'])) : 'Not Specified';

    // Input validation
    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); // Bad Request
        echo json_encode(["success" => false, "message" => "Invalid input: Name and a valid email are required."]);
        exit;
    }

    // Debugging: Log collected values
    error_log("Received values: Name=$name, Email=$email, Phone=$phone, Subject=$subject, Type of Use=$type_of_use, Power Requirement=$power_requirement, Message=$message");

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'khodani.madi@gmail.com'; // Your Gmail address
        $mail->Password   = 'xzybimjcrreqykcj'; // App password (Use environment variable in production)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('khodani.madi@gmail.com', 'MuneSolar');
        $mail->addAddress('admin@rtbest.co.za', 'MuneSolar');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
            <strong>Name:</strong> $name<br>
            <strong>Email:</strong> $email<br>
            <strong>Phone:</strong> $phone<br>
            <strong>Type of Use:</strong> $type_of_use<br>
            <strong>Power Requirement:</strong> $power_requirement<br>
            <strong>Message:</strong> $message
        ";

        // Send the email
        $mail->send();
        echo json_encode(["success" => true, "message" => "Contact form submitted and email sent successfully."]);

    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Failed to send the message.",
            "error" => $mail->ErrorInfo // Include the actual error message
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed."]);
}
