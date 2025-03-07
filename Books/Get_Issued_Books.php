<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

include "../Db_Connection.php"; // Include database connection

try {
    // Get the JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if EnrollmentNo is provided
    if (!isset($data['EnrollmentNo'])) {
        echo json_encode(["status" => "error", "message" => "EnrollmentNo is required"]);
        exit;
    }

    $EnrollmentNo = $data['EnrollmentNo'];

    // Fetch issued books along with the title from books table
    $stmt = $conn->prepare("
        SELECT  ib.book_id, b.title , ib.issued_date, ib.last_date, ib.renew_left
               
        FROM issued_books ib
        JOIN books b ON ib.book_id = b.book_id
        WHERE ib.EnrollmentNo = (SELECT EnrollmentNo FROM users WHERE EnrollmentNo = ?)
    ");
    $stmt->execute([$EnrollmentNo]);

    $issuedBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($issuedBooks) > 0) {
        echo json_encode(["status" => "success", "data" => $issuedBooks]);
    } else {
        echo json_encode(["status" => "error", "message" => "No issued books found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
