<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

include "../Db_Connection.php"; // Include database connection

$data = json_decode(file_get_contents("php://input"), true);

// Validate book_id
if (!isset($data["book_id"])) {
    echo json_encode(["status" => "error", "message" => "Missing book_id"]);
    exit;
}

$book_id = htmlspecialchars(strip_tags($data["book_id"]));

try {
    // Check if the book exists
    $stmt = $conn->prepare("SELECT * FROM books WHERE book_id = :book_id");
    $stmt->execute(["book_id" => $book_id]);

    if ($stmt->rowCount() == 0) {
        echo json_encode(["status" => "error", "message" => "Book not found"]);
        exit;
    }

    // Delete book from database
    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = :book_id");
    $stmt->execute(["book_id" => $book_id]);

    echo json_encode(["status" => "success", "message" => "Book deleted successfully"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
