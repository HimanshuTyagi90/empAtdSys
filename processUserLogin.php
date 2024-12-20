<?php
session_start();

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'eams');

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Validate credentials
$sql = "SELECT * FROM users WHERE email = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $_SESSION['userId'] = $user['userId']; // Assuming 'userId' is the primary key in users table

    // Return success response with userId
    echo json_encode(['success' => true, 'userId' => $user['userId']]);
} else {
    // Invalid credentials, return an error message
    echo json_encode(['success' => false, 'message' => 'Invalid email ID or Password']);
}

$stmt->close();
$conn->close();
?>
