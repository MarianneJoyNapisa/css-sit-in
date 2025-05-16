<?php
session_start();
error_reporting(E_ALL);
header('Content-Type: application/json');
include '../db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Both fields are required."]);
        exit();
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password, idno, firstname, lastname, remaining_sessions FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password, $id_num, $firstname, $lastname, $remaining_sessions);
        $stmt->fetch();
        
        error_log("Stored Hash for $username: " . $hashed_password);
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['id_number'] = $id_num ?? '';
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['remaining_sessions'] = $remaining_sessions;

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
