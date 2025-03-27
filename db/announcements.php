<?php
session_start();
header("Content-Type: application/json");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include '../db/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

// Handle POST request (insert new announcement)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (empty($_POST['announcementTitle']) || empty($_POST['announcementContent'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Title and content are required']);
        exit();
    }

    // Sanitize input
    $title = htmlspecialchars($_POST['announcementTitle']);
    $content = htmlspecialchars($_POST['announcementContent']);

    // Insert the announcement into the database
    $sql = "INSERT INTO announcements (title, content) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("ss", $title, $content);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Announcement posted successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Error posting announcement: ' . $stmt->error]);
    }

    $stmt->close();
}

// Handle GET request (fetch announcements)
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch announcements from the database
    $sql = "SELECT * FROM announcements ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }

    if ($result->num_rows > 0) {
        $announcements = [];
        while ($row = $result->fetch_assoc()) {
            $announcements[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $announcements]);
    } else {
        echo json_encode(['status' => 'success', 'data' => []]); // No announcements found
    }
}

// Handle unsupported request methods
else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}

$conn->close();
?>