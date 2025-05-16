<?php
// reset_sessions.php
require_once '../db/db_connection.php';

header('Content-Type: application/json');

// Check if it's a request for a specific student or all students
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    // Handle individual student session reset
    $studentId = mysqli_real_escape_string($conn, $_POST['student_id']);
    
    // Updated query to use idno instead of username if that's your primary key
    $query = "UPDATE users SET remaining_sessions = 30 WHERE idno = ? AND username != 'admin'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $studentId);
    mysqli_stmt_execute($stmt);
    
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode([
            'status' => 'success', 
            'message' => "Sessions reset for student ID $studentId"
        ]);
    } else {
        // Add error logging for debugging
        error_log("Failed to reset sessions for student ID: $studentId");
        echo json_encode([
            'status' => 'error', 
            'message' => "Failed to reset sessions. Student not found or no changes made."
        ]);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}

// Handle reset all sessions (original functionality)
$query = "UPDATE users SET remaining_sessions = 30 WHERE username != 'admin'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_affected_rows($conn) > 0) {
    echo json_encode([
        'status' => 'success', 
        'message' => 'All sessions have been reset'
    ]);
} else {
    error_log("Failed to reset all sessions: " . mysqli_error($conn));
    echo json_encode([
        'status' => 'error', 
        'message' => 'Error resetting sessions: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>