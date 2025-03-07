<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

require "../Db_Connection.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "../vendor/autoload.php"; // PHPMailer library

// Read the raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($data["enrollment"]) && isset($data["email"])) {
    $enrollment = $data["enrollment"];
    $email = $data["email"];

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT StudentName, Email FROM users WHERE EnrollmentNo = :enrollment");
    $stmt->execute([":enrollment" => $enrollment]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if the entered email matches the one in the database
        if ($user["Email"] === $email) {
            // Generate a new temporary password
            $tempPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
            $hashedPassword = password_hash($tempPassword, PASSWORD_BCRYPT);

            // Update the password in the database
            $updateStmt = $conn->prepare("UPDATE users SET Password = :password WHERE EnrollmentNo = :enrollment");
            $updateStmt->execute([":password" => $hashedPassword, ":enrollment" => $enrollment]);

            // Send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.hostinger.com'; // Replace with SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'connect@wayvbiz.com'; 
                $mail->Password = '#Connect2024'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('connect@wayvbiz.com', 'Admin');
                $mail->addAddress($user["Email"]);

                $mail->Subject = "Reset Password Request";
                $mail->Body = "Hello " . $user["StudentName"] . ",\n\nYour new temporary password is: " . $tempPassword . "\n\nPlease log in and change your password immediately.";

                $mail->send();
                echo json_encode(["status" => "success", "message" => "A new password has been sent to your email."]);
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => "Mail could not be sent: " . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "The email you entered does not match our records. Please enter a valid email."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
