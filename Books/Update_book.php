<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require "../Db_Connection.php"; // Include database connection

$data = json_decode(file_get_contents("php://input"), true);

// Validate if ISBN is provided
if (!isset($data["isbn"])) {
    echo json_encode(["status" => "error", "message" => "Missing ISBN"]);
    exit;
}

// Remove non-numeric characters from ISBN (to handle formats like 978-1234567890)
$isbn = preg_replace("/[^0-9]/", "", htmlspecialchars(strip_tags($data["isbn"])));

$fields = ["title", "author", "publication_year", "total_issued", "status"];
$updateFields = [];
$params = [];

foreach ($fields as $field) {
    if (isset($data[$field])) {
        $updateFields[] = "$field = :$field";
        $params[":$field"] = htmlspecialchars(strip_tags($data[$field]));
    }
}

if (empty($updateFields)) {
    echo json_encode(["status" => "error", "message" => "No fields to update"]);
    exit;
}

$params[":isbn"] = $isbn;

$sql = "UPDATE books SET " . implode(", ", $updateFields) . " WHERE REPLACE(isbn, '-', '') = :isbn";
$stmt = $conn->prepare($sql);

try {
    $stmt->execute($params);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Book updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No book found with this ISBN"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
