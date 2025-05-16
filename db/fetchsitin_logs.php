<?php
include '../db/db_connection.php';

header("Content-Type: application/json");

if (!$conn) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit();
}

try {
    $sql = "SELECT 
            s.id, 
            s.id_number, 
            s.name, 
            s.purpose, 
            s.lab, 
            s.sessions AS sessions_used,
            u.remaining_sessions,
            s.status, 
            s.time_in,
            IFNULL(s.timeout, '') AS timeout, 
            s.points_added,
            s.created_at
        FROM sit_in_logs s
        JOIN users u ON s.id_number = u.idno
        WHERE s.status IN ('Active', 'Reserved') OR DATE(s.time_in) = CURDATE()
        ORDER BY 
            CASE 
                WHEN s.status = 'Active' THEN 0 
                WHEN s.status = 'Reserved' THEN 1
                ELSE 2 
            END,
            s.time_in DESC";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $sitInLogs = [];
    while ($row = $result->fetch_assoc()) {
        $sitInLogs[] = $row;
    }

    if (empty($sitInLogs)) {
        echo json_encode([
            "status" => "success",
            "message" => "No records found",
            "data" => [],
            "debug" => [
                "query" => $sql,
                "tables_exist" => [
                    "sit_in_logs" => tableExists($conn, 'sit_in_logs'),
                    "users" => tableExists($conn, 'users')
                ]
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "success",
            "data" => $sitInLogs
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage(),
        "sql" => $sql ?? null
    ]);
}

$conn->close();

function tableExists($conn, $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    return $result->num_rows > 0;
}
?>