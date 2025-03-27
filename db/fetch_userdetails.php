<?php
session_start();
include 'db_connection.php'; // Ensure this file establishes a valid `$conn`

header("Content-Type: application/json");

// Enable error reporting for debugging (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check database connection
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Check if admin is searching for a specific student
if (isset($_GET['idNumber'])) {
    $idNumber = trim($_GET['idNumber']); // Trim to remove unnecessary spaces

    $sql = "SELECT idno, firstname, middlename, lastname, image, remaining_sessions FROM users WHERE idno = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $idNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            $userData['fullname'] = $userData['lastname'] . ', ' . $userData['firstname'] . ' ' . 
                                   (!empty($userData['middlename']) ? strtoupper($userData['middlename'][0]) . '.' : '');
            echo json_encode(["status" => "success", "data" => $userData]);
        } else {
            // Instead of an error message, return an empty success response
            echo json_encode(["status" => "success", "data" => null]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
    }
} 
// Default: Fetch details of the logged-in user
else if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT firstname, middlename, lastname, image, remaining_sessions FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            echo json_encode(["status" => "success", "data" => $userData]);
        } else {
            echo json_encode(["status" => "error", "message" => "User not found"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
}

$conn->close();
?>
