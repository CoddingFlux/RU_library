<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../Db_Connection.php"; // Include database connection

$response = [];

if (!isset($_POST["UID"])) {
    echo json_encode(["status" => "error", "message" => "Missing UID"]);
    exit;
}

$UID = htmlspecialchars(strip_tags($_POST["UID"]));

// Fetch fields that can be updated
$updateFields = [];
$allowedFields = ["full_name", "email", "phone", "course", "course_year", "department", "current_address", "permanent_address", "DOB"];

foreach ($allowedFields as $field) {
    if (isset($_POST[$field])) {
        $updateFields[$field] = htmlspecialchars(strip_tags($_POST[$field]));
    }
}

// Handle profile image upload
if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] === UPLOAD_ERR_OK) {
    $targetDir = "uploads/"; // Directory to store uploaded images
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileExt = strtolower(pathinfo($_FILES["profile"]["name"], PATHINFO_EXTENSION));
    $allowedExt = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($fileExt, $allowedExt)) {
        echo json_encode(["status" => "error", "message" => "Invalid file format. Only JPG, PNG, and GIF allowed."]);
        exit;
    }

    // Custom file name format using UID (e.g., UID_12345.jpg)
    $newFileName = "UID_" . $UID . "." . $fileExt;
    $targetFilePath = $targetDir . $newFileName;

    // Delete old profile image if exists
    $stmt = $conn->prepare("SELECT profile FROM user_info WHERE UID = :UID");
    $stmt->execute(["UID" => $UID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !empty($user["profile"])) {
        $oldImagePath = $user["profile"];
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath); // Delete old image
        }
    }

    if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFilePath)) {
        $updateFields["profile"] = $targetFilePath; // Store file path in database
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload profile image"]);
        exit;
    }
}

// If no data to update, return error
if (empty($updateFields)) {
    echo json_encode(["status" => "error", "message" => "No data provided for update"]);
    exit;
}

try {
    // Construct the SQL query dynamically
    $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($updateFields)));
    $updateFields["UID"] = $UID; // Add UID to the binding array

    $stmt = $conn->prepare("UPDATE user_info SET $setClause WHERE UID = :UID");

    if ($stmt->execute($updateFields)) {
        echo json_encode(["status" => "success", "message" => "User information updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update user information"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
?>
