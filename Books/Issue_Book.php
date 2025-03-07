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

    // Check if book is available
    $stmt = $conn->prepare("SELECT status FROM books WHERE book_id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        echo json_encode(["status" => "error", "message" => "Book not found"]);
        exit;
    }

    if ($book['status'] !== 'available') {
        echo json_encode(["status" => "error", "message" => "Book is not available"]);
        exit;
    }

    // Calculate last date (e.g., 14 days from today)
    $issued_date = date("Y-m-d");
    $last_date = date("Y-m-d", strtotime("+14 days"));

    // Insert into issued_books
    $stmt = $conn->prepare("INSERT INTO issued_books (book_id, EnrollmentNo, issued_date, last_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$book_id, $EnrollmentNo, $issued_date, $last_date]);

    // Update total_issued count
    $stmt = $conn->prepare("UPDATE books SET total_issued = total_issued + 1 WHERE book_id = ?");
    $stmt->execute([$book_id]);

    // Send notification
    sendNotification($conn, $EnrollmentNo, "Book_issued", "Your Book ID: $book_id has been issued successfully. Due date: $last_date.");

    echo json_encode(["status" => "success", "message" => "Book issued successfully"]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
