<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

require "../Db_Connection.php"; 

try {
    // Count total books
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_books FROM books");
    $stmt->execute();
    $totalBooks = $stmt->fetch(PDO::FETCH_ASSOC)["total_books"];

    // Count total users
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_users FROM users");
    $stmt->execute();
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)["total_users"];

    // Count total issued books
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_issued_books FROM issued_books");
    $stmt->execute();
    $totalIssuedBooks = $stmt->fetch(PDO::FETCH_ASSOC)["total_issued_books"];

    // Count total due returns (books past last_date)
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_due_returns FROM issued_books WHERE last_date < CURDATE()");
    $stmt->execute();
    $totalDueReturns = $stmt->fetch(PDO::FETCH_ASSOC)["total_due_returns"];

    // Fetch top 5 most issued books
    $stmt = $conn->prepare("SELECT book_id, title, total_issued FROM books ORDER BY total_issued DESC LIMIT 5");
    $stmt->execute();
    $popularBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode([
        "status" => "success",
        "data" => [
            "total_books" => $totalBooks,
            "total_users" => $totalUsers,
            "total_issued_books" => $totalIssuedBooks,
            "total_due_returns" => $totalDueReturns,
            "popular_books" => $popularBooks
        ]
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
