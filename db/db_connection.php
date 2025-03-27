<?php
header('Content-Type: application/json'); // Ensure JSON response

$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "ccssitin";
$port = 3307;

$conn = mysqli_connect($host, $user, $password, $database, $port);

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . mysqli_connect_error()]);
    exit();
}
?>