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
        
        // Validate header row (ignore case)
        $expectedHeaders = ["EnrollmentNo", "StudentName", "PhoneStudent", "Email", "EmailAlternate", "AcademicYearName", "ProgramName", "Semester"];
        $fileHeaders = array_map('strtolower', $rows[0]);
        $expectedHeadersLower = array_map('strtolower', $expectedHeaders);
        
        if ($fileHeaders !== $expectedHeadersLower) {
            echo json_encode(["status" => "error", "message" => "Invalid file format. Check headers."]);
            exit;
        }

        unset($rows[0]); // Remove header row

        $stmt = $conn->prepare("INSERT INTO users (EnrollmentNo, StudentName, PhoneStudent, Email, EmailAlternate, AcademicYearName, ProgramName, Semester, Password) 
                                VALUES (:enrollmentNo, :studentName, :phoneStudent, :email, :emailAlternate, :academicYearName, :programName, :semester, NULL)");

        foreach ($rows as $row) {
            $stmt->execute([
                ":enrollmentNo" => htmlspecialchars(strip_tags($row[0])),
                ":studentName" => htmlspecialchars(strip_tags($row[1])),
                ":phoneStudent" => htmlspecialchars(strip_tags($row[2])),
                ":email" => htmlspecialchars(strip_tags($row[3])),
                ":emailAlternate" => htmlspecialchars(strip_tags($row[4])),
                ":academicYearName" => htmlspecialchars(strip_tags($row[5])),
                ":programName" => htmlspecialchars(strip_tags($row[6])),
                ":semester" => (int)$row[7]
            ]);
        }

        echo json_encode(["status" => "success", "message" => "Students uploaded successfully"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error processing file: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>

