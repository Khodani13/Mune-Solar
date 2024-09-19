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
    // Collect and sanitize POST data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']); // Capture the phone number
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.afrihost.co.za';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'admin@rtbest.co.za';
        $mail->Password   = '73!azwi94'; // Use environment variable in production
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('admin@rtbest.co.za', 'MuneSolar');
        $mail->addAddress('admin@rtbest.co.za', 'MuneSolar');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "Name: $name<br>Email: $email<br>Phone: $phone<br>Message: $message"; // Include phone number

        $mail->send();
        echo json_encode(["success" => true, "message" => "Contact form submitted and email sent successfully."]);

    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        http_response_code(500);
        echo json_encode(["message" => "Failed to send the message.", "error" => $mail->ErrorInfo, "exception" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed."]);
}
?>
