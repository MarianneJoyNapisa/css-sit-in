<?php
include '../db/db_connection.php';  // Make sure this path is correct
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $input = file_get_contents('php://input');
    parse_str($input, $data);
    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing sit-in ID",
            "received_data" => $data  // For debugging
        ]);
        exit();
    }

    try {
        $stmt = $conn->prepare("UPDATE sit_in_logs SET status = 'Active', time_in = NOW() WHERE id = ? AND status = 'Reserved'");  // Changed 'Reserve' to 'Reserved'
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Sit-in session timed in successfully."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "No record updated. Either sit-in not found or not in 'Reserved' status.",
                    "debug_info" => [
                        "id_received" => $id,
                        "possible_status" => "Check if status is 'Reserved' (with 'd')"
                    ]
                ]);
            }
        } else {
            throw new Exception("Database update failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage(),
            "error_details" => $conn->error  // MySQL error details
        ]);
    } finally {
        if (isset($stmt)) $stmt->close();
        $conn->close();
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Expected POST.",
        "received_method" => $_SERVER['REQUEST_METHOD']
    ]);
}
?>