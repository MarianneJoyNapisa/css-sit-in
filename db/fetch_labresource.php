<?php
header('Content-Type: application/json');
include '../db/db_connection.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    $sql = "SELECT * FROM lab_resources";
    
    // Add search condition if search term exists
    if (!empty($search)) {
        $sql .= " WHERE title LIKE ? OR description LIKE ?";
        $searchTerm = "%$search%";
    }
    
    $sql .= " ORDER BY uploaded_at DESC";
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($search)) {
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $type = ($row['file_type'] === 'pdf') ? 'PDF' : 'Link';
        $link = ($row['file_type'] === 'pdf') 
            ? '../uploads/' . basename($row['file_path']) 
            : $row['file_path'];

        $data[] = [
            'title' => $row['title'],
            'type' => $type,
            'link' => $link,
            'description' => $row['description'] ?? '' // Add if you have descriptions
        ];
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>