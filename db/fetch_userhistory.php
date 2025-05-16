<?php
include '../db/db_connection.php';

header("Content-Type: application/json");
date_default_timezone_set('Asia/Manila');

// Get the id_number from query parameter
$id_number = $_GET['id_number'] ?? null;

if (!$id_number) {
    http_response_code(400);
    die(json_encode(["status" => "error", "message" => "Missing ID number"]));
}

try {
    $stmt = $conn->prepare("SELECT * FROM sit_in_history WHERE id_number = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $history = [];
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    
    echo json_encode([
        "status" => "success",
        "data" => $history
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

$conn->close();
?>