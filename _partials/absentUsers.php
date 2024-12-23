<?php
// Connect to the database (using the correct database name)
$conn = new mysqli('localhost', 'root', '', 'eams');
date_default_timezone_set('Asia/Kolkata'); // Indian Standard Time
// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Get the current date and time
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');
$currentDay = date('D'); // Get the current day of the week

// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['userId'];
        $tableName = 'user_' . $userId;  // Dynamically generate table name based on userId

        // Check if attendance for today exists in the user-specific table
        $checkSql = "SELECT * FROM $tableName WHERE date = '$currentDate'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows == 0) {
            // If attendance not found, mark as "Absent"
            $status = 'Absent';

            // Insert absent status for today
            $insertSql = "INSERT INTO $tableName (date, day, time, status) VALUES ('$currentDate', '$currentDay', '$currentTime', '$status')";
            if ($conn->query($insertSql) === TRUE) {
                echo "Marked Absent for user: $userId\n";
            } else {
                echo "Failed to mark absent for user: $userId\n";
            }
        }
    }
    echo "Attendance marking process completed.";
} else {
    echo "No users found.";
}

$conn->close();
?>
