<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require "../Db_Connection.php"; // Include database connection
require "../vendor/autoload.php"; // Ensure the correct path to autoload.php

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_FILES["file"])) {
        echo json_encode(["status" => "error", "message" => "No file uploaded"]);
        exit;
    }

    $fileName = $_FILES["file"]["tmp_name"];

    if (!file_exists($fileName)) {
        echo json_encode(["status" => "error", "message" => "File not found"]);
        exit;
    }

    try {
        $spreadsheet = IOFactory::load($fileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        

        // Validate header row
        $expectedHeaders = ["book_id", "title", "author", "isbn", "publication_year", "total_issued"];
        if ($rows[0] !== $expectedHeaders) {
            echo json_encode(["status" => "error", "message" => "Invalid file format. Check headers."]);
            exit;
        }

        unset($rows[0]); // Remove header row

        $stmt = $conn->prepare("INSERT INTO books (book_id, title, author, isbn, publication_year, total_issued) 
            VALUES (:book_id, :title, :author, :isbn, :publication_year, :total_issued)");

        foreach ($rows as $row) {
            $stmt->execute([
                ":book_id" => htmlspecialchars(strip_tags($row[0])),
                ":title" => htmlspecialchars(strip_tags($row[1])),
                ":author" => htmlspecialchars(strip_tags($row[2])),
                ":isbn" => htmlspecialchars(strip_tags($row[3])),
                ":publication_year" => htmlspecialchars(strip_tags($row[4])),
                ":total_issued" => (int) $row[5]
            ]);
        }

        echo json_encode(["status" => "success", "message" => "Books uploaded successfully"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error processing file: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?> 
