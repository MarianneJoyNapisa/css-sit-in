<?php
header('Content-Type: application/json'); // Ensure JSON response
include __DIR__ . '/db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if all necessary fields exist
    $required_fields = ['idno', 'lastname', 'firstname', 'middlename', 'email', 'course', 'yearlvl', 'username', 'password', 'confirmPassword'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode(["status" => "error", "message" => ucfirst($field) . " is required!", "type" => "error"]);
            exit();
        }
    }

    // Collect and sanitize input data
    $idno = mysqli_real_escape_string($conn, $_POST['idno']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $yearlvl = mysqli_real_escape_string($conn, $_POST['yearlvl']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format!", "type" => "error"]);
        exit();
    }

    // Validate password strength
    if (strlen($password) < 6) {
        echo json_encode(["status" => "error", "message" => "Password must be at least 6 characters long!", "type" => "error"]);
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match!", "type" => "error"]);
        exit();
    }

    // Check if username already exists
    $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $checkUsernameQuery);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(["status" => "error", "message" => "Username already exists!", "type" => "error"]);
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $insertQuery = "INSERT INTO users (idno, lastname, firstname, middlename, email, course, yearlvl, username, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "sssssssss", $idno, $lastname, $firstname, $middlename, $email, $course, $yearlvl, $username, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "message" => "Registration successful! Redirecting to login...", "type" => "success", "redirect" => "login.php"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . mysqli_error($conn), "type" => "error"]);
    }

    exit();
}
?>