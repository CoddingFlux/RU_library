<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include "../Db_Connection.php"; // Include database connection

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["name"], $data["email"], $data["phone"], $data["EID"], $data["current_address"], $data["permanent_address"], $data["DOB"], $data["password"])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

$name = htmlspecialchars(strip_tags($data["name"]));
$email = filter_var($data["email"], FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars(strip_tags($data["phone"]));
$EID = htmlspecialchars(strip_tags($data["EID"]));
$current_address = htmlspecialchars(strip_tags($data["current_address"]));
$permanent_address = htmlspecialchars(strip_tags($data["permanent_address"]));
$DOB = htmlspecialchars(strip_tags($data["DOB"]));
$password = password_hash($data["password"], PASSWORD_BCRYPT); // Hash password
$user_role = htmlspecialchars(strip_tags($data["user_role"]));

try {
    // Check if email or phone already exists
    $stmt = $conn->prepare("SELECT id FROM administrator WHERE email = :email OR phone = :phone");
    $stmt->execute(["email" => $email, "phone" => $phone]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Email or phone already exists"]);
        exit;
    }

    // Insert new administrator
    $stmt = $conn->prepare("INSERT INTO administrator (name, email, phone, EID, current_address, permanent_address, DOB, password, user_role) 
                            VALUES (:name, :email, :phone, :EID, :current_address, :permanent_address, :DOB, :password, :user_role)");

    $stmt->execute([
        "name" => $name,
        "email" => $email,
        "phone" => $phone,
        "EID" => $EID,
        "current_address" => $current_address,
        "permanent_address" => $permanent_address,
        "DOB" => $DOB,
        "password" => $password,
        "user_role" => $user_role
    ]);

    echo json_encode(["status" => "success", "message" => "Administrator registered successfully"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
?>
