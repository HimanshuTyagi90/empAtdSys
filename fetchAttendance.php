<?php
session_start();

// Get userId from the session
$userId = $_SESSION['userId'] ?? 0;

if ($userId == 0) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Validate userId (ensure it's numeric to prevent SQL injection)
if (!is_numeric($userId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

// Sanitize and dynamically construct table name
$tableName = 'user_' . intval($userId);

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'eams');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if the table exists before querying
$tableExists = $conn->query("SHOW TABLES LIKE '$tableName'");
if ($tableExists->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Attendance table does not exist']);
    $conn->close();
    exit;
}

// Fetch attendance data
$sql = "SELECT * FROM `$tableName` ORDER BY `date` DESC"; // Use backticks for table names
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(['success' => false, 'message' => 'Error executing query: ' . $conn->error]);
    $conn->close();
    exit;
}

$attendanceData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendanceData[] = [
            'date' => $row['date'],    // Replace with actual column names
            'day' => $row['day'],      // Replace with actual column names
            'time' => $row['time'],    // Replace with actual column names
            'status' => $row['status'] // Replace with actual column names
        ];
    }
    // Return the data as JSON
    echo json_encode(['success' => true, 'data' => $attendanceData]);
} else {
    echo json_encode(['success' => false, 'message' => 'No attendance records found']);
}

$conn->close();
?>
