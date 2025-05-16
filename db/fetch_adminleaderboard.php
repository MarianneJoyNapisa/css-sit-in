<?php
include '../db/db_connection.php';

$query = "SELECT idno, firstname, lastname, course, yearlvl, points 
          FROM users
          WHERE username NOT LIKE '%admin%' 
          ORDER BY points DESC, lastname ASC";

$result = $conn->query($query);

// Check for query error
if (!$result) {
    die("Query failed: " . $conn->error);
}

$leaderboard = [];

while ($row = $result->fetch_assoc()) {
    $leaderboard[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $leaderboard
]);
?>
