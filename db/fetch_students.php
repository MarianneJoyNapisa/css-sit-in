<?php
// Include your DB connection file
require_once '../db/db_connection.php';

// Function to fetch students with pagination
function fetchStudents($page = 1, $perPage = 10) {
    global $conn;

    // Calculate the offset for pagination
    $offset = ($page - 1) * $perPage;

    // SQL query to fetch non-admin users
    $query = "SELECT idno, CONCAT(lastname, ', ', firstname) AS full_name, yearlvl, course, remaining_sessions 
                FROM users 
                WHERE username != 'admin'  -- Exclude 'admin' username
                ORDER BY id 
                LIMIT $perPage OFFSET $offset";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    $students = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
    }

    return $students;
}

// Function to get the total number of students (excluding admin)
function getTotalStudents() {
    global $conn;

    // SQL query to count the total number of non-admin users
    $query = "SELECT COUNT(*) as total FROM users WHERE role != 'admin'";
    $result = mysqli_query($conn, $query);

    // Fetch the total count
    $totalStudents = 0;
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $totalStudents = $row['total'];
    }

    return $totalStudents;
}

// If this script is being called directly for fetching student data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the current page from the URL, or default to 1
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 10;

    // Fetch the students and total count
    $students = fetchStudents($page, $perPage);
    $totalStudents = getTotalStudents();
    $totalPages = ceil($totalStudents / $perPage);

    // Prepare the response as JSON
    $response = [
        'students' => $students,
        'totalStudents' => $totalStudents,
        'totalPages' => $totalPages,
        'perPage' => $perPage
    ];

    // Output the response
    echo json_encode($response);
}
?>
