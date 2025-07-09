<?php
// filepath: C:\xampp\htdocs\Smart-Pantry-Manager\login.php
session_start(); // Add this line at the top
header('Content-Type: application/json');
$conn = new mysqli('localhost:3307', 'root', 'u59tAt80Aa', 'smart_pantry');
if ($conn->connect_error) {
    http_response_code(500);
    exit('DB error');
}

$data = json_decode(file_get_contents('php://input'), true);
$email = $conn->real_escape_string($data['email']);
$password = $data['password'];

$q = $conn->query("SELECT * FROM users WHERE email='$email'");
if ($q->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    exit;
}
$user = $q->fetch_assoc();
if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id']; // Store user ID in session
    $_SESSION['username'] = $user['username'];
    echo json_encode(['success' => true, 'username' => $user['username']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}
?>