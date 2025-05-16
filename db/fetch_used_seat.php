<?php
require_once '../db/db_connection.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$laboratory = $data['laboratory'] ?? '';

if (empty($laboratory)) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit();
}

try {
    // Get currently used seats (approved reservations)
    $stmt = $pdo->prepare("
        SELECT seat_number 
        FROM reservations 
        WHERE laboratory = ? AND status = 'approved'
        AND date = CURDATE()  -- Only show today's reservations
    ");
    $stmt->execute([$laboratory]);
    $usedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    
    echo json_encode(['usedSeats' => $usedSeats]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}