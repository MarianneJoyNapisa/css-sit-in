<?php
session_start();
include '../db/db_connection.php';

header('Content-Type: application/json');

// Verify admin access
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

if ($_SESSION['username'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$feedbackId = $_POST['id'] ?? null;

if (!$feedbackId) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid feedback ID']);
    exit();
}

try {
    $stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->bind_param("i", $feedbackId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Feedback deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Feedback not found']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>