<?php
require_once '../db/db_connection.php';
header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$laboratory = $data['laboratory'] ?? '';
$seatStatus = $data['seatStatus'] ?? [];

if (empty($laboratory) || empty($seatStatus)) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

try {
    $conn->begin_transaction();
    
    // First delete existing statuses
    $stmt = $conn->prepare("DELETE FROM seat_status WHERE laboratory = ?");
    $stmt->bind_param("s", $laboratory);
    $stmt->execute();
    
    // Insert new statuses
    $stmt = $conn->prepare("INSERT INTO seat_status (laboratory, seat_number, status) VALUES (?, ?, ?)");
    
    foreach ($seatStatus as $seat => $status) {
        $stmt->bind_param("sis", $laboratory, $seat, $status);
        $stmt->execute();
    }
    
    $conn->commit();
    echo json_encode(["status" => "success"]);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>