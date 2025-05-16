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

// SQL query to fetch sit-in logs
$sql = "SELECT id, id_number, name, purpose, lab, sessions, time_in, timeout AS time_out, status, points_added
        FROM sit_in_logs
        ORDER BY time_in DESC";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
    exit;
}

$logs = [];

while ($row = $result->fetch_assoc()) {
    $earnedPoints = (int)$row['points_added']; // Points earned from this sit-in log
    $id_number = $conn->real_escape_string($row['id_number']);

    // Fetch user data
    $userSql = "SELECT remaining_sessions, points, bonus_applied_points FROM users WHERE idno = '$id_number'";
    $userResult = $conn->query($userSql);

    if ($userResult && $userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();

        $userPoints = (int)$userRow['points'];
        $alreadyApplied = (int)$userRow['bonus_applied_points'];
        $remainingSessions = (int)$userRow['remaining_sessions'];

        // Calculate how many bonus sessions should be applied now
        $bonusEligible = floor($userPoints / 3);
        $bonusAlreadyGiven = floor($alreadyApplied / 3);
        $newBonusToApply = $bonusEligible - $bonusAlreadyGiven;

        if ($newBonusToApply > 0) {
            // Apply the bonus
            $updateSql = "UPDATE users 
                          SET remaining_sessions = remaining_sessions + $newBonusToApply,
                              bonus_applied_points = bonus_applied_points + (" . ($newBonusToApply * 3) . ")
                          WHERE idno = '$id_number'";
            $conn->query($updateSql);

            // Update the local variable too for display purposes
            $remainingSessions += $newBonusToApply;
        }

        // Optional: include adjusted sessions in the report
        $row['adjusted_remaining_sessions'] = $remainingSessions;
    }

    // Include earned points in this log
    $row['earned_points'] = $earnedPoints;

    $logs[] = $row;
}

// Send response
echo json_encode(["status" => "success", "data" => $logs]);

$conn->close();
?>
