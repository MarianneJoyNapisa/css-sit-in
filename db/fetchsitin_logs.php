<?php
include '../db/db_connection.php';

header("Content-Type: application/json");

$sql = "SELECT id, id_number, name, purpose, lab, sessions, status, timeout FROM sit_in_logs ORDER BY created_at DESC";
$result = $conn->query($sql);

$sitInLogs = [];

while ($row = $result->fetch_assoc()) {
    $sitInLogs[] = $row;
}

echo json_encode(["status" => "success", "data" => $sitInLogs]);

$conn->close();
?>
