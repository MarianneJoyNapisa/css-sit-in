<?php
session_start();
header('Content-Type: application/json');
include '../db/db_connection.php';

// ✅ Admin check based on username, not role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Forbidden."]);
    exit;
}

// ✅ Add missing `status` check column if you plan to use it
$sql = "SELECT * FROM reservations WHERE status = 'pending'"; // Make sure 'status' column exists in the table
$result = $conn->query($sql);

$reservations = [];

while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

echo json_encode(["status" => "success", "data" => $reservations]);
?>
