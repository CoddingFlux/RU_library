<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

require "../Db_Connection.php";

function sendNotification($conn, $EnrollmentNo, $title, $description) {
    $stmt = $conn->prepare("INSERT INTO notifications (EnrollmentNo, noti_title, description, status) VALUES (?, ?, ?, 'unseen')");
    $stmt->execute([$EnrollmentNo, $title, $description]);
}

try {
    // Get JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['book_id']) || !isset($data['EnrollmentNo'])) {
        echo json_encode(["status" => "error", "message" => "book_id and EnrollmentNo are required"]);
        exit;
    }

    $book_id = $data['book_id'];
    $EnrollmentNo = $data['EnrollmentNo'];

    // Check if the book was issued
    $stmt = $conn->prepare("SELECT * FROM issued_books WHERE book_id = ? AND EnrollmentNo = ? ORDER BY issued_date DESC LIMIT 1");
    $stmt->execute([$book_id, $EnrollmentNo]);
    $issuedBook = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$issuedBook) {
        echo json_encode(["status" => "error", "message" => "Book was not issued or already returned"]);
        exit;
    }

    // Delete from issued_books (mark as returned)
    $stmt = $conn->prepare("DELETE FROM issued_books WHERE book_id = ? AND EnrollmentNo = ?");
    $stmt->execute([$book_id, $EnrollmentNo]);

    // Update book status to available
    $stmt = $conn->prepare("UPDATE books SET status = 'available' WHERE book_id = ?");
    $stmt->execute([$book_id]);

    // Send notification for book return
    sendNotification($conn, $EnrollmentNo, "Book_returned", "Your Book ID: $book_id has been returned successfully.");

    echo json_encode(["status" => "success", "message" => "Book returned successfully"]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>