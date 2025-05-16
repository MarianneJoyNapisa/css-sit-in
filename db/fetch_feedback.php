<?php
session_start();
include '../db/db_connection.php';

// Set headers first to prevent any accidental output
header('Content-Type: application/json');

// Disable error display to ensure clean JSON output
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Verify admin access
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

if ($_SESSION['username'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

try {
    // Check database connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed");
    }

    $query = "
        SELECT f.*, u.username, s.name, s.lab, s.purpose 
        FROM feedback f
        JOIN users u ON f.user_id = u.id
        LEFT JOIN sit_in_history s ON f.sit_in_id = s.id
        ORDER BY f.created_at DESC
    ";

    $stmt = $conn->prepare($query);
    
    // Check if prepare succeeded
    if ($stmt === false) {
        throw new Exception("SQL prepare error: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $feedback = [];
    
    while ($row = $result->fetch_assoc()) {
        $feedback[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $feedback
    ]);
    
} catch (Exception $e) {
    // Log the actual error for debugging
    error_log("Feedback fetch error: " . $e->getMessage());
    
    // Return a sanitized error message
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to load feedback data'
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>