<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['status' => 'error', 'message' => 'Unknown error'];

try {
    $query = "
        SELECT 
            r.id, 
            r.user_id,
            r.fullname,
            r.laboratory,
            r.seat_number,
            r.date,
            r.time_slot,
            r.purpose,
            r.status,
            r.processed_date,
            r.processed_by
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        WHERE r.status = 'denied'
        ORDER BY r.processed_date DESC
    ";

    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $reservations = [];

    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }

    $response = [
        'status' => 'success',
        'data' => $reservations
    ];

} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => 'Failed to fetch denied reservations: ' . $e->getMessage()
    ];
} finally {
    if ($stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode($response);
    exit();
}
?>
