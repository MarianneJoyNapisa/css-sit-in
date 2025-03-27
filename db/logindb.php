<?php
session_start();
error_reporting(E_ALL);
header('Content-Type: application/json'); // Ensure JSON response
include '../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Both fields are required."]);
        exit();
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        // Debugging: Log stored password hash
        error_log("Stored Hash for $username: " . $hashed_password);
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Check if the user is an admin
            if ($username === 'admin') {
                echo json_encode(["status" => "success", "message" => "Admin login successful!", "redirect" => "admin/adminDashboard.php"]);
            } else {
                echo json_encode(["status" => "success", "message" => "User login successful!", "redirect" => "user/userDashboard.php"]);
            }
        } else {
            error_log("Password verification failed for $username.");
            echo json_encode(["status" => "error", "message" => "Invalid password."]);
        }
    } else {
        error_log("Invalid username: $username.");
        echo json_encode(["status" => "error", "message" => "Invalid username."]);
    }

    $stmt->close();
}

$conn->close();
?>
