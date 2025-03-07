<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

require "../Db_Connection.php"; // Include database connection

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"], $data["password"])) {
    echo json_encode(["status" => "error", "message" => "Missing id or password"]);
    exit;
}

$id = htmlspecialchars(strip_tags($data["id"])); // Could be UID or EID
$password = $data["password"];

try {
    // Check if the identifier is in user_info (UID)
    $stmt = $conn->prepare("SELECT EnrollmentNo, password FROM users WHERE EnrollmentNo = :id");
    $stmt->execute(["id" => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        echo json_encode([
            "status" => "success",
            "message" => "User login successful",
            "user_role" => "user",
            "id" => $user["EnrollmentNo"]
        ]);
        exit;
    }

    // Check if the identifier is in administrator (EID)
    $stmt = $conn->prepare("SELECT EID, password, user_role FROM administrator WHERE EID = :id");
    $stmt->execute(["id" => $id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin["password"])) {
        echo json_encode([
            "status" => "success",
            "message" => ucfirst($admin["user_role"]) . " login successful",
            "user_role" => $admin["user_role"],
            "id" => $admin["EID"]
        ]);
        exit;
    }

    // If no match found
    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
?>
