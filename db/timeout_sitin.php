<?php
include '../db/db_connection.php';

header("Content-Type: application/json");

// Ensure PHP uses the correct timezone
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        echo json_encode(["status" => "error", "message" => "Missing sit-in ID"]);
        exit();
    }

    // Get the current time in Asia/Manila
    $timeoutTime = date("Y-m-d H:i:s");

    // Correct SQL statement to ensure timezone consistency
    $sql = "UPDATE sit_in_logs SET status = 'Timed Out', timeout = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("si", $timeoutTime, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Sit-in timed out successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to timeout sit-in"]);
    }

    $stmt->close();
}

$conn->close();
?>
