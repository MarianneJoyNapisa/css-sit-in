<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../db/db_connection.php'; // Database connection

header("Content-Type: application/json");

// Check if the database connection is valid
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// SQL query to fetch sit-in logs (including timed-out status)
$sql = "SELECT id, id_number, name, purpose, lab, sessions, time_in, timeout AS time_out, status
        FROM sit_in_logs
        ORDER BY time_in DESC"; // Adjusted for time_in and ordered by most recent

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
    exit;
}

// Convert fetched data into JSON format
$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

// Send JSON response with the fetched data
echo json_encode(["status" => "success", "data" => $logs]);

$conn->close();
?>
