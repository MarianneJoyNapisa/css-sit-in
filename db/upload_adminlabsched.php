<?php
include '../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $links = $_POST['links'] ?? [];
    $availability = $_POST['availability'] ?? [];

    foreach ($links as $lab_number => $schedule_link) {
        $avail = $availability[$lab_number] ?? 'unavailable';

        // Check if lab_number already exists
        $checkStmt = $conn->prepare("SELECT id FROM lab_schedules WHERE lab_number = ?");
        $checkStmt->bind_param("i", $lab_number);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Update
            $stmt = $conn->prepare("UPDATE lab_schedules SET schedule_link = ?, availability = ? WHERE lab_number = ?");
            $stmt->bind_param("ssi", $schedule_link, $avail, $lab_number);
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO lab_schedules (lab_number, schedule_link, availability) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $lab_number, $schedule_link, $avail);
        }

        $stmt->execute();
        $stmt->close();
    }

    header("Location: ../admin/adminLabSchedules.php");
    exit();
}
?>
