<?php
include '../db/db_connection.php';

$idno = $_POST['idno'] ?? '';
$award_type = $_POST['award_type'] ?? '';

if (empty($idno) || empty($award_type)) {
    die(json_encode(["status" => "error", "message" => "Missing parameters"]));
}

// First get user details
$user_query = "SELECT firstname, lastname FROM users WHERE idno = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $idno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(["status" => "error", "message" => "User not found"]));
}

$user = $result->fetch_assoc();
$user_name = $user['firstname'] . ' ' . $user['lastname'];

// First remove any existing award of this type
$delete_query = "DELETE FROM user_awards WHERE award_type = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("s", $award_type);
$stmt->execute();

// Then insert the new award
$insert_query = "INSERT INTO user_awards (idno, user_name, award_type) VALUES (?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("sss", $idno, $user_name, $award_type);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to assign award"]);
}
?>