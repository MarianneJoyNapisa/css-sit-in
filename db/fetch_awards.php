<?php
include '../db/db_connection.php';

// Query to fetch current awards
$query = "SELECT * FROM user_awards";
$result = $conn->query($query);

$awards = [
    'most_active' => null,
    'top_performing' => null
];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $awards[$row['award_type']] = [
            'idno' => $row['idno'],
            'name' => $row['user_name']
        ];
    }
}

echo json_encode([
    "status" => "success",
    "most_active" => $awards['most_active'],
    "top_performing" => $awards['top_performing']
]);
?>