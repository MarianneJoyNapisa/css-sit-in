<?php
session_start();
header("Content-Type: application/json");

// Include the database connection file
include '../db/db_connection.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

// Get the search term from the query string
$searchTerm = $_GET['searchTerm'] ?? '';

if (empty($searchTerm)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Search term is required']);
    exit();
}

// Search for users in the database
$sql = "SELECT * FROM users WHERE firstname LIKE ? OR lastname LIKE ? OR idno LIKE ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$searchTerm = "%$searchTerm%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $users]);
} else {
    echo json_encode(['status' => 'success', 'data' => []]); // No users found
}

$stmt->close();
$conn->close();
?>
