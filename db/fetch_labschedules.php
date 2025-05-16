<?php
include '../db/db_connection.php';

$sql = "SELECT lab_number, schedule_link, availability, last_updated FROM lab_schedules ORDER BY lab_number ASC";
$result = $conn->query($sql);

$schedules = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($schedules);
?>
