<?php
// reset_sessions.php
require_once '../db/db_connection.php';

// SQL query to reset all student sessions to a default value (e.g., 10 sessions)
$query = "UPDATE users SET remaining_sessions = 30 WHERE username != 'admin'";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(['message' => 'All sessions have been reset']);
} else {
    echo json_encode(['message' => 'Error resetting sessions']);
}
?>