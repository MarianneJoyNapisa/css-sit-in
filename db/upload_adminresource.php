<?php
include '../db/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $fileType = $_POST['file_type'];
    $filePath = "";

    if ($fileType == 'pdf' && isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $targetDir = "../uploads/";
        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                die("Failed to create upload directory.");
            }
        }
        $filename = uniqid() . '_' . basename($_FILES["pdf_file"]["name"]); // Add unique ID to prevent conflicts
        $targetFilePath = $targetDir . $filename;

        // Check if file is a PDF
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        if($fileType != "pdf") {
            die("Only PDF files are allowed.");
        }

        if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $targetFilePath)) {
            $filePath = $targetFilePath;
        } else {
            die("File upload failed. Error: " . $_FILES["pdf_file"]["error"]);
        }
    } elseif ($fileType == 'link') {
        $filePath = filter_var($_POST['google_link'], FILTER_SANITIZE_URL);
        if (!filter_var($filePath, FILTER_VALIDATE_URL)) {
            die("Invalid URL provided.");
        }
    } else {
        die("Invalid submission.");
    }

    $stmt = $conn->prepare("INSERT INTO lab_resources (title, description, file_type, file_path) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $title, $description, $fileType, $filePath);
    if (!$stmt->execute()) {
        die("Execution failed: " . $stmt->error);
    }

    header("Location: ../admin/adminLabResourceMaterials.php");
    exit;
}
?>