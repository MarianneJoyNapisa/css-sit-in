<?php
include '../db/db_connection.php';

// Query to fetch users' leaderboard with award information
$query = "SELECT 
            u.idno, 
            u.firstname, 
            u.lastname, 
            u.course, 
            u.yearlvl, 
            u.points,
            MAX(CASE WHEN a.award_type = 'most_active' THEN 1 ELSE 0 END) as most_active,
            MAX(CASE WHEN a.award_type = 'top_performing' THEN 1 ELSE 0 END) as top_performing,
            CASE 
                WHEN MAX(a.id) IS NOT NULL THEN 1 
                ELSE 0 
            END as has_awards
          FROM users u
          LEFT JOIN user_awards a ON u.idno = a.idno
          WHERE u.idno IS NOT NULL AND u.username != 'admin'
          GROUP BY u.idno, u.firstname, u.lastname, u.course, u.yearlvl, u.points
          ORDER BY u.points DESC, u.lastname ASC";

$result = $conn->query($query);

// Check for query error
if (!$result) {
    die(json_encode(["status" => "error", "message" => "Query failed: " . $conn->error]));
}

$leaderboard = [];

// Fetch all the data and store it in an array
while ($row = $result->fetch_assoc()) {
    $leaderboard[] = $row;
}

// Return the result as JSON
echo json_encode([
    "status" => "success",
    "data" => $leaderboard
]);
?>