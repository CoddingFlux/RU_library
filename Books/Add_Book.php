<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

include "../Db_Connection.php"; // Include database connection

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data["book_id"], $data["title"], $data["author"], $data["isbn"], $data["publication_year"], $data["status"], $data["total_issued"])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

// Sanitize input data
$book_id = htmlspecialchars(strip_tags($data["book_id"]));
$title = htmlspecialchars(strip_tags($data["title"]));
$author = htmlspecialchars(strip_tags($data["author"]));
$isbn = htmlspecialchars(strip_tags($data["isbn"]));
$publication_year = htmlspecialchars(strip_tags($data["publication_year"]));
$status = htmlspecialchars(strip_tags($data["status"]));
$total_issued = (int) $data["total_issued"];

try {
    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO books (book_id, title, author, isbn, publication_year, status, total_issued) VALUES (:book_id, :title, :author, :isbn, :publication_year, :category, :status, :issued, :total_issued)");

    $stmt->execute([
        ":book_id" => $book_id,
        ":title" => $title,
        ":author" => $author,
        ":isbn" => $isbn,
        ":publication_year" => $publication_year,
        ":status" => $status,
        ":total_issued" => $total_issued
    ]);

    echo json_encode(["status" => "success", "message" => "Book added successfully"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?> 
