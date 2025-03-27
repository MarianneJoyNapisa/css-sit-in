<?php
session_start();
include '../db/db_connection.php'; // Ensure this file establishes a valid `$conn`

header("Content-Type: application/json");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check database connection
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// **Fetch Profile (GET)**
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT username, idno, lastname, firstname, middlename, email, course, yearlvl, image FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(["status" => "success", "data" => $result->fetch_assoc()]);
        } else {
            echo json_encode(["status" => "error", "message" => "User not found"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
    }
    exit;
}

// **Handle Profile Update (POST - Multipart Form Data)**
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = [];

    // Handle file upload (profile picture)
    if (!empty($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../images/";
        $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        $maxFileSize = 2 * 1024 * 1024; // 2MB limit

        if ($_FILES['profile_pic']['size'] > $maxFileSize) {
            $response['status'] = "error";
            $response['message'] = "File is too large (Max 2MB).";
            echo json_encode($response);
            exit;
        }

        if (!in_array($_FILES['profile_pic']['type'], $allowedTypes)) {
            $response['status'] = "error";
            $response['message'] = "Invalid file type. Only JPG, PNG, and GIF allowed.";
            echo json_encode($response);
            exit;
        }

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $newFilename = "user_" . $user_id . "_" . time() . "." . $extension;
        $filePath = $uploadDir . $newFilename;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $filePath)) {
            // Update the image path in the database
            $update_sql = "UPDATE users SET image = ? WHERE id = ?";
            if ($stmt = $conn->prepare($update_sql)) {
                $stmt->bind_param("si", $newFilename, $user_id);
                if ($stmt->execute()) {
                    $_SESSION['image'] = $newFilename; // Update session variable
                    $response['status'] = "success";
                    $response['message'] = "Profile picture updated successfully!";
                    $response['image'] = $newFilename;
                } else {
                    $response['status'] = "error";
                    $response['message'] = "Failed to update profile picture in the database.";
                }
                $stmt->close();
            } else {
                $response['status'] = "error";
                $response['message'] = "SQL Error: " . $conn->error;
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Failed to upload profile picture.";
        }
    }

    // Handle other profile fields
    $fields = ['idno', 'lastname', 'firstname', 'middlename', 'email', 'course', 'yearlvl'];
    $update_fields = [];
    $update_values = [];
    $types = "";

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $update_fields[] = "$field = ?";
            $update_values[] = trim($_POST[$field]);
            $types .= "s";
        }
    }

    if (!empty($update_fields)) {
        $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = ?";
        $update_values[] = $user_id;
        $types .= "i";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param($types, ...$update_values);
            if ($stmt->execute()) {
                $response['status'] = "success";
                $response['message'] = "Profile updated successfully!";
            } else {
                $response['status'] = "error";
                $response['message'] = "Failed to update profile: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['status'] = "error";
            $response['message'] = "SQL Error: " . $conn->error;
        }
    }

    // Return the response
    echo json_encode($response);
    exit;
}

echo json_encode(["status" => "error", "message" => "Invalid request method."]);
?>