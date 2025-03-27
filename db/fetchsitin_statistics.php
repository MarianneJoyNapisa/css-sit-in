<?php
include '../db/db_connection.php'; // Assuming the database connection is in this file

// Fetch statistics
$sql = "SELECT COUNT(*) AS studentRegistered FROM users";
$result = $conn->query($sql);
$studentRegistered = $result->fetch_assoc()['studentRegistered'];

$sql = "SELECT COUNT(*) AS currentlySitIn FROM sit_in_logs WHERE status = 'active'"; // Assuming there's a 'status' column
$result = $conn->query($sql);
$currentSitIn = $result->fetch_assoc()['currentlySitIn'];

$sql = "SELECT COUNT(*) AS totalSitIn FROM sit_in_logs";
$result = $conn->query($sql);
$totalSitIn = $result->fetch_assoc()['totalSitIn'];

// Get Purpose Counts
$purposes = ["C# Programming", "Java Programming", "Web Development", "Cisco Packet Tracer", "Python Programming", "PHP Programming"];
$purposeCounts = [];

foreach ($purposes as $purpose) {
    $sql = "SELECT COUNT(*) AS count FROM sit_in_logs WHERE purpose = '$purpose'";
    $result = $conn->query($sql);
    $purposeCounts[$purpose] = $result->fetch_assoc()['count'];
}

// Return data as JSON
echo json_encode([
    'studentRegistered' => $studentRegistered,
    'currentlySitIn' => $currentSitIn,
    'totalSitIn' => $totalSitIn,
    'purposeCounts' => $purposeCounts
]);
?>
