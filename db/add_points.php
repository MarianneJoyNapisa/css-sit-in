<?php
require_once '../db/db_connection.php'; // Adjust as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sitInId = $_POST['sit_in_id'];
    $idno = $_POST['idno'];

    // Check if point already added
    $stmt = $conn->prepare("SELECT points_added FROM sit_in_logs WHERE id = ?");
    $stmt->bind_param("i", $sitInId); // Binding the parameter (sit_in_id) as an integer
    $stmt->execute();
    $log = $stmt->get_result()->fetch_assoc();

    if (!$log || $log['points_added']) {
        echo json_encode(["status" => "error", "message" => "Point already given or record not found."]);
        exit;
    }

    // Begin transaction
    $conn->autocommit(false); // Disable autocommit to start a transaction

    try {
        // 1. Update points in users table
        $updateUser = $conn->prepare("UPDATE users SET points = points + 1 WHERE idno = ?");
        $updateUser->bind_param("s", $idno); // Bind the parameter as a string
        $updateUser->execute();

        // 2. Mark sit_in_logs.points_added = 1
        $updateLog = $conn->prepare("UPDATE sit_in_logs SET points_added = 1 WHERE id = ?");
        $updateLog->bind_param("i", $sitInId); // Bind the parameter as an integer
        $updateLog->execute();

        // Commit the transaction
        $conn->commit();

        echo json_encode(["status" => "success", "message" => "Point successfully added."]);
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Failed to add point."]);
    } finally {
        $conn->autocommit(true); // Re-enable autocommit
    }
}
?>