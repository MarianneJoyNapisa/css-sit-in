<?php
include '../db/db_connection.php';

$award_type = $_POST['award_type'] ?? '';

if (empty($award_type)) {
    die(json_encode(["status" => "error", "message" => "Missing parameters"]));
}

$query = "DELETE FROM user_awards WHERE award_type = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $award_type);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to remove award"]);
}
?>