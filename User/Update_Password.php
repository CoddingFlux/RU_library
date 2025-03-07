<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

require "../Db_Connection.php";

function sendNotification($conn, $EnrollmentNo, $title, $description) {
    $stmt = $conn->prepare("INSERT INTO notifications (EnrollmentNo, noti_title, description, status) VALUES (?, ?, ?, 'unseen')");
    $stmt->execute([$EnrollmentNo, $title, $description]);
}

try {
    // Get JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['EnrollmentNo']) || !isset($data['new_password'])) {
        echo json_encode(["status" => "error", "message" => "EnrollmentNo and new_password are required"]);
        exit;
    }

    $EnrollmentNo = $data['EnrollmentNo'];
    $new_password = password_hash($data['new_password'], PASSWORD_BCRYPT);

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE EnrollmentNo = ?");
    $stmt->execute([$EnrollmentNo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    // Update password
    $stmt = $conn->prepare("UPDATE users SET Password = ? WHERE EnrollmentNo = ?");
    $stmt->execute([$new_password, $EnrollmentNo]);

    // Send notification for password update
    sendNotification($conn, $EnrollmentNo, "password_updated", "Your password has been updated successfully.");

    echo json_encode(["status" => "success", "message" => "Password updated successfully"]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
