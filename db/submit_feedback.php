<?php
session_start();
include '../db/db_connection.php';
include '../db/profanity_filter.php'; // Include the profanity filter functions

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$sit_in_id = $_POST['sit_in_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$comments = $_POST['comments'] ?? '';

// Validate required fields
if (!$sit_in_id || !$rating) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit();
}

// Server-side profanity check
if (containsProfanity($comments, $profanityList)) {
    echo json_encode(['status' => 'error', 'message' => 'Feedback contains inappropriate language. Please revise your comments.']);
    exit();
}

try {
    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO feedback (sit_in_id, user_id, rating, comments, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $sit_in_id, $_SESSION['user_id'], $rating, $comments);
    $stmt->execute();
    
    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Feedback submitted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit feedback']);
    }
} catch (Exception $e) {
    // Log the error for debugging (optional)
    error_log('Feedback submission error: ' . $e->getMessage());
    
    // Return a generic error message to the client
    echo json_encode(['status' => 'error', 'message' => 'Database error. Please try again later.']);
} finally {
    // Close the statement and connection
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>