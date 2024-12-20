<?php
// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Check if JSON is valid
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

// Extract form data
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$firstName = $input['firstName'] ?? '';
$lastName = $input['lastName'] ?? '';
$phone = $input['phone'] ?? '';
$latitude = $input['latitude'] ?? '';
$longitude = $input['longitude'] ?? '';

// Validate inputs
if (empty($email) || empty($password) || empty($firstName) || empty($lastName) || empty($phone) || empty($latitude) || empty($longitude)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'eams');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Insert user data into the database
$sql = "INSERT INTO users (email, password, firstName, lastName, phone, latitude, longitude)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Check if prepare failed
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL statement']);
    exit;
}

$stmt->bind_param('sssssss', $email, $password, $firstName, $lastName, $phone, $latitude, $longitude);
$stmt->execute();

// Get the last inserted userId
$userId = $stmt->insert_id;

// Check if the insertion was successful
if ($userId) {
    // Create a user-specific table for the new user
    $tableName = 'user_' . (int)$userId; // Sanitize the table name to ensure it’s a valid integer

    // SQL query to create a table
    $createTableSql = "CREATE TABLE $tableName (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        date DATE NOT NULL,
                        day VARCHAR(10) NOT NULL,
                        time TIME NOT NULL,
                        status VARCHAR(10) NOT NULL
                    )";

    // Execute the query to create the table
    if ($conn->query($createTableSql) === TRUE) {
        echo json_encode(['success' => true, 'userId' => $userId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create user-specific table']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create account']);
}

$stmt->close();
$conn->close();
?>
