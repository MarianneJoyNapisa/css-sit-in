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

if (empty($laboratory)) {
    echo json_encode(["status" => "error", "message" => "Laboratory required"]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT seat_number, status FROM seat_status WHERE laboratory = ?");
    $stmt->bind_param("s", $laboratory);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $seatStatus = [];
    while ($row = $result->fetch_assoc()) {
        $seatStatus[$row['seat_number']] = $row['status'];
    }
    
    // Fill defaults for any missing seats
    for ($i = 1; $i <= 48; $i++) {
        if (!isset($seatStatus[$i])) {
            $seatStatus[$i] = 'available';
        }
    }
    
    echo json_encode([
        "status" => "success",
        "seatStatus" => $seatStatus
    ]);
    
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>