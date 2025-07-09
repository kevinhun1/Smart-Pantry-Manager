<?php
session_start();
header('Content-Type: application/json');
$conn = new mysqli('localhost:3307', 'root', 'u59tAt80Aa', 'smart_pantry');
if ($conn->connect_error) {
    http_response_code(500);
    exit('DB error');
}

$user_id = $_SESSION['user_id'] ?? 1; // For demo, fallback to user 1

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $item_name = $conn->real_escape_string($data['item_name']);
    $manufacture_date = $conn->real_escape_string($data['manufacture_date']);
    $expiration_date = $conn->real_escape_string($data['expiration_date']);
    $conn->query("INSERT INTO pantry_items (user_id, item_name, manufacture_date, expiration_date) VALUES ($user_id, '$item_name', '$manufacture_date', '$expiration_date')");
    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $item_id = intval($data['id']);
    $conn->query("DELETE FROM pantry_items WHERE id=$item_id AND user_id=$user_id");
    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT id, item_name, manufacture_date, expiration_date FROM pantry_items WHERE user_id=$user_id ORDER BY added_at DESC");
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    echo json_encode($items);
    exit;
}
?>