<?php
// filepath: C:\xampp\htdocs\Smart-Pantry-Manager\register.php
header('Content-Type: application/json');
$conn = new mysqli('localhost:3307', 'root', 'u59tAt80Aa', 'smart_pantry');
if ($conn->connect_error) { http_response_code(500); exit('DB error'); }

$data = json_decode(file_get_contents('php://input'), true);
$username = $conn->real_escape_string($data['username']);
$email = $conn->real_escape_string($data['email']);
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$phone = $conn->real_escape_string($data['phone']);

$q = $conn->query("SELECT id FROM users WHERE email='$email'");
if ($q->num_rows > 0) {
    echo json_encode(['success'=>false, 'message'=>'Email already registered']);
    exit;
}

$conn->query("INSERT INTO users (username, email, password, phone) VALUES ('$username', '$email', '$password', '$phone')");
echo json_encode(['success'=>true]);

?>