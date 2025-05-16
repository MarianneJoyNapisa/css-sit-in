<?php
header('Content-Type: application/json');
include '../db/db_connection.php';

// Get raw POST data
$input = file_get_contents("php://input");

// Check if data was received
if (empty($input)) {
    echo json_encode([
        "status" => "error",
        "message" => "No data received"
    ]);
    exit();
}

$data = json_decode($input, true);

// Check for JSON decode errors
if ($data === null) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON data received",
        "error_details" => json_last_error_msg()
    ]);
    exit();
}

// Validate required field (only laboratory now)
if (!isset($data['laboratory'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing laboratory parameter"
    ]);
    exit();
}

try {
    $laboratory = $data['laboratory'];

    // Get approved reservations (today and future) that are NOT completed
    $sql = "SELECT seat_number FROM reservations 
        WHERE laboratory = ? 
        AND status = 'approved'
        AND date = ?
        AND time_slot = ?
        AND (status != 'completed' OR status IS NULL)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $laboratory, $data['date'], $data['time']);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservedSeats = [];
    while ($row = $result->fetch_assoc()) {
        $reservedSeats[] = $row['seat_number'];
    }

    // Get permanently unavailable seats from seat_status
    $sql = "SELECT seat_number FROM seat_status 
            WHERE laboratory = ? 
            AND status = 'unavailable'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $laboratory);
    $stmt->execute();
    $result = $stmt->get_result();

    $unavailableSeats = [];
    while ($row = $result->fetch_assoc()) {
        $unavailableSeats[] = $row['seat_number'];
    }

    // Combine both arrays (unique values)
    $occupiedSeats = array_unique(array_merge($reservedSeats, $unavailableSeats));

    echo json_encode([
        "status" => "success",
        "occupiedSeats" => $occupiedSeats
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error occurred",
        "error_details" => $e->getMessage()
    ]);
}
?>
