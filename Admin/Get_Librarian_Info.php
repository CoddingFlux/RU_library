<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include "../Db_Connection.php"; // Include database connection

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["EID"])) {
    echo json_encode(["status" => "error", "message" => "Missing EID"]);
    exit;
}

$EID = htmlspecialchars(strip_tags($data["EID"]));

try {
    // Fetch administrator details
    $stmt = $conn->prepare("SELECT name, email, phone, current_address, permanent_address, DOB, profile, user_role FROM administrator WHERE EID = :EID");
    $stmt->execute(["EID" => $EID]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        echo json_encode(["status" => "error", "message" => "Administrator not found"]);
        exit;
    }

    // Return administrator data
    echo json_encode([
        "status" => "success",
        "admin" => $admin
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
?>
