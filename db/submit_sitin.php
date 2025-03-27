<?php
session_start();
include '../db/db_connection.php';

header("Content-Type: application/json");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idNumber = $_POST['idNumber'];
    $studentName = $_POST['studentName'];
    $purpose = $_POST['purpose'];
    $lab = $_POST['lab'];

    // Fetch user's remaining sessions
    $query = "SELECT remaining_sessions FROM users WHERE idno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $idNumber);
    $stmt->execute();
    $stmt->bind_result($remaining_sessions);
    $stmt->fetch();
    $stmt->close();

    if ($remaining_sessions > 0) {
        // Decrement remaining sessions
        $updated_sessions = $remaining_sessions - 1;

        // Insert sit-in log
        $insert_sql = "INSERT INTO sit_in_logs (id_number, name, purpose, lab, sessions, status) VALUES (?, ?, ?, ?, ?, 'Active')";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssssi", $idNumber, $studentName, $purpose, $lab, $updated_sessions);
        $stmt->execute();
        $stmt->close();

        // Update remaining sessions in users table
        $update_sql = "UPDATE users SET remaining_sessions = ? WHERE idno = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("is", $updated_sessions, $idNumber);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "success", "message" => "Sit-in logged successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No remaining sessions available."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
