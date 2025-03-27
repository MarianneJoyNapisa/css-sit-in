<?php
header('Content-Type: application/json');
include '../db/db_connection.php';

$id_number = $_GET['id_number'] ?? null;

if (!$id_number) {
    echo json_encode(['status' => 'error', 'message' => 'ID Number is required']);
    exit();
}

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "SELECT 
                id,
                id_number,
                name,
                purpose,
                lab,
                DATE_FORMAT(time_in, '%h:%i %p') AS time_in,
                DATE_FORMAT(timeout, '%h:%i %p') AS time_out,
                DATE_FORMAT(created_at, '%Y-%m-%d') AS date
            FROM sit_in_logs
            WHERE id_number = ? AND status = 'Timed Out'
            ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        // Only include completed sessions (timed out)
        if ($row['status'] === 'Timed Out') {
            $logs[] = $row;
        }
    }
    
    if (empty($logs)) {
        echo json_encode(['status' => 'success', 'message' => 'No history found', 'data' => []]);
    } else {
        echo json_encode(['status' => 'success', 'data' => $logs]);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>