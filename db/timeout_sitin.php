<?php
// Add error reporting at the TOP (remove in production)
error_reporting(0);
ini_set('display_errors', 0);

include '../db/db_connection.php';

// Set headers FIRST before any output
header("Content-Type: application/json");
date_default_timezone_set('Asia/Manila');

// Validate request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die(json_encode(["status" => "error", "message" => "Method not allowed"]));
}

// Get input
$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    http_response_code(400);
    die(json_encode(["status" => "error", "message" => "Missing sit-in ID"]));
}

try {
    // Start transaction
    $conn->begin_transaction();
    $timeoutTime = date("Y-m-d H:i:s");

    // 1. Get sit-in log details
    $getSql = "SELECT * FROM sit_in_logs WHERE id = ?";
    $getStmt = $conn->prepare($getSql);
    if (!$getStmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $getStmt->bind_param("i", $id);
    $getStmt->execute();
    $result = $getStmt->get_result();
    $sit_in = $result->fetch_assoc();
    $getStmt->close();

    if (!$sit_in) {
        throw new Exception("Sit-in record not found");
    }

    // 2. Update the sit_in_logs table
    $updateSql = "UPDATE sit_in_logs SET status = 'Timed Out', timeout = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    if (!$updateStmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $updateStmt->bind_param("si", $timeoutTime, $id);
    $updateStmt->execute();

    if ($updateStmt->affected_rows === 0) {
        throw new Exception("No sit-in record found to update");
    }
    $updateStmt->close();

    // 3. Decrement user's remaining sessions
    $decrementSql = "UPDATE users SET remaining_sessions = remaining_sessions - 1 
                     WHERE idno = ? AND remaining_sessions > 0";
    $decrementStmt = $conn->prepare($decrementSql);
    if (!$decrementStmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $decrementStmt->bind_param("s", $sit_in['id_number']);
    $decrementStmt->execute();
    $decrementStmt->close();

    // 3.5. If the sit-in was a reservation, mark reservation as completed
    if ($sit_in['status'] === 'Reserved') {
        // Update ALL reservations for this seat/lab/date (not just by sit_in_id)
        $updateResStmt = $conn->prepare("UPDATE reservations 
                                        SET status = 'completed' 
                                        WHERE laboratory = ? 
                                        AND seat_number = ? 
                                        AND date = DATE(?)
                                        AND status = 'approved'");
        $updateResStmt->bind_param("sis", $sit_in['lab'], $sit_in['seat_number'], $sit_in['time_in']);
        $updateResStmt->execute();
        
        if ($updateResStmt->affected_rows > 0) {
            error_log("Marked ".$updateResStmt->affected_rows." reservations as completed");
        }
        $updateResStmt->close();
    }
    // 4. Insert into history table
    $insertSql = "INSERT INTO sit_in_history SELECT * FROM sit_in_logs WHERE id = ?";
    $insertStmt = $conn->prepare($insertSql);
    if (!$insertStmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $insertStmt->bind_param("i", $id);
    $insertStmt->execute();
    $insertStmt->close();

    // Commit transaction
    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Sit-in timed out, session deducted, and reservation updated if applicable"
    ]);

} catch (Exception $e) {
    if ($conn) {
        $conn->rollback();
    }

    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

// Close connection
if ($conn) {
    $conn->close();
}
?>
