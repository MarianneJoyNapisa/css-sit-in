<?php
include '../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['lab_number'], $data['availability'])) {
        $lab = intval($data['lab_number']);
        $availability = $data['availability'] === 'available' ? 'available' : 'unavailable';

        $stmt = $conn->prepare("UPDATE lab_schedules SET availability = ? WHERE lab_number = ?");
        $stmt->bind_param("si", $availability, $lab);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update availability."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid input data."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
