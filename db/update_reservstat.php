<?php
header('Content-Type: application/json');
include '../db/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['reservation_id']) || !isset($data['status'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit();
}

try {
    $conn->begin_transaction();

    // Get reservation details
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $data['reservation_id']);
    $stmt->execute();
    $reservation = $stmt->get_result()->fetch_assoc();

    if (!$reservation) {
        throw new Exception("Reservation not found");
    }

    // Update reservation status
    $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $data['status'], $data['reservation_id']);
    $stmt->execute();

    // If approved, insert into sit_in_logs
    if ($data['status'] === 'approved') {
        $timeSlot = $reservation['time_slot'];
        $timeParts = explode('-', $timeSlot);
        $startTime = trim($timeParts[0]);
        $timeIn = $reservation['date'] . ' ' . $startTime;

        $stmt = $conn->prepare("
            INSERT INTO sit_in_logs 
            (id_number, name, purpose, lab, sessions, status, time_in, points_added) 
            VALUES (?, ?, ?, ?, 1, 'Reserved', ?, 0)
        ");
        $stmt->bind_param(
            "sssss",
            $reservation['id_number'],
            $reservation['fullname'],
            $reservation['purpose'],
            $reservation['laboratory'],
            $timeIn
        );
        $stmt->execute();
        $sit_in_id = $conn->insert_id;

        // If column exists, update reservation
        if (columnExists($conn, 'reservations', 'sit_in_id')) {
            $stmt = $conn->prepare("UPDATE reservations SET sit_in_id = ? WHERE id = ?");
            $stmt->bind_param("ii", $sit_in_id, $data['reservation_id']);
            $stmt->execute();
        }
    }

    // ✅ Insert Notification
    if (columnExists($conn, 'reservations', 'user_id')) {
        $userId = $reservation['user_id'];
        $status = $data['status'];
        $message = $status === 'approved'
            ? "Your seat reservation has been approved."
            : "Your seat reservation has been denied.";

        $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $message);
        $stmt->execute();
    }

    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Reservation ' . $data['status'] . ' successfully'
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'status' => 'error',
        'message' => 'Error processing request: ' . $e->getMessage()
    ]);
}

function columnExists($conn, $table, $column) {
    $result = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
    return $result && $result->num_rows > 0;
}
?>
