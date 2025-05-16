<?php
session_start();
header('Content-Type: application/json');
include '../db/db_connection.php';

// Validate session and required data
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(["status" => "error", "message" => "Unauthorized access"]));
}

// Get and validate input
$input = json_decode(file_get_contents("php://input"), true);
if (!$input) {
    http_response_code(400);
    die(json_encode(["status" => "error", "message" => "Invalid request data"]));
}

$required = ['laboratory', 'seat', 'date', 'time', 'purpose'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        die(json_encode(["status" => "error", "message" => "Missing required field: $field"]));
    }
}

try {
    // Prepare data
    $user_id = $_SESSION['user_id'];
    $id_number = $_SESSION['id_number'];
    $fullname = ($_SESSION['firstname'] ?? '') . ' ' . ($_SESSION['lastname'] ?? '');
    $laboratory = $input['laboratory'];
    $seat_number = (int)$input['seat'];
    $date = $input['date'];
    $time_slot = $input['time']; // Should be in "HH:MM-HH:MM" format
    $purpose = $input['purpose'];

    // Validate date is not in the past
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        http_response_code(400);
        die(json_encode(["status" => "error", "message" => "Cannot reserve for past dates"]));
    }

    // Check seat availability
    $checkSql = "SELECT id FROM reservations 
                WHERE laboratory = ? 
                AND seat_number = ? 
                AND date = ? 
                AND time_slot = ?
                AND status != 'completed'";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("siss", $laboratory, $seat_number, $date, $time_slot);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        http_response_code(409);
        die(json_encode(["status" => "error", "message" => "Seat already reserved for this time slot"]));
    }

    // Create reservation
    $insertSql = "INSERT INTO reservations 
                 (user_id, id_number, fullname, laboratory, seat_number, date, time_slot, purpose, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("isssisss", $user_id, $id_number, $fullname, $laboratory, $seat_number, $date, $time_slot, $purpose);
    
    if ($stmt->execute()) {
        // Success - return reservation ID
        echo json_encode([
            "status" => "success",
            "message" => "Reservation submitted successfully",
            "reservation_id" => $conn->insert_id
        ]);
    } else {
        throw new Exception("Failed to create reservation");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error", 
        "message" => "Reservation failed: " . $e->getMessage()
    ]);
}
?>