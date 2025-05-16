<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize response
$response = ['status' => 'error', 'message' => 'Unknown error'];

try {
    // Check database connection
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

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
        WHERE r.status = 'approved'
        ORDER BY r.processed_date DESC
    ";
    
    $stmt = $conn->prepare($query);
    
    // Check if prepare was successful
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // Execute the statement
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
    $response['message'] = $e->getMessage();
    error_log("Error in fetch_approved_reserv.php: " . $e->getMessage());

}

finally {
    // Close statement if it was successfully prepared
    if ($stmt instanceof mysqli_stmt) {
        $stmt->close();
    }

    // Close connection
    if (isset($conn)) {
        $conn->close();
    }

    echo json_encode($response);
    exit();
}
?>