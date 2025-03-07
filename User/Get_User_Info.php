<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include "../Db_Connection.php"; // Include database connection

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["EnrollmentNo"])) {
    echo json_encode(["status" => "error", "message" => "Missing EnrollmentNo"]);
    exit;
}

$EnrollmentNo = htmlspecialchars(strip_tags($data["EnrollmentNo"]));

try {
    // Prepare SQL query
    $stmt = $conn->prepare("SELECT EnrollmentNo, StudentName, PhoneStudent, Email, EmailAlternate, AcademicYearName, ProgramName, Semester FROM users WHERE EnrollmentNo = :EnrollmentNo");
    $stmt->execute(["EnrollmentNo" => $EnrollmentNo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    // Return user info
    echo json_encode([
        "status" => "success",
        "message" => "User data fetched successfully",
        "user" => $user
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
?>
