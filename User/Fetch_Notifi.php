<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require "../Db_Connection.php";

try {
    // Get JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data["EnrollmentNo"])) {
        echo json_encode(["status" => "error", "message" => "Missing EnrollmentNo"]);
        exit;
    }

    $EnrollmentNo = htmlspecialchars(strip_tags($data["EnrollmentNo"]));

    // Fetch all notifications for the user
    $stmt = $conn->prepare("SELECT id, noti_title, description, status, created_at FROM notifications WHERE EnrollmentNo = ? ORDER BY created_at DESC");
    $stmt->execute([$EnrollmentNo]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "message" => "Notifications fetched successfully",
        "notifications" => $notifications
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
?>
