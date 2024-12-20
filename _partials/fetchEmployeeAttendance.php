<?php
// fetchEmployeeAttendance.php

// Check if empid is passed in the query string
if (isset($_GET['empid'])) {
    $empid = $_GET['empid'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'eams');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Dynamically create the table name
    $tableName = 'user_' . intval($empid); // Ensure empid is treated as an integer to prevent SQL injection

    // Query to get the attendance data from the dynamically created table
    $sql = "SELECT * FROM `$tableName` ORDER BY date DESC";  // Use backticks around table names for dynamic tables
    $result = $conn->query($sql);

    $attendance = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $attendance[] = [
                'date' => $row['date'],    // Replace with actual column names
                'day' => $row['day'],      // Replace with actual column names
                'time' => $row['time'],    // Replace with actual column names
                'status' => $row['status'] // Replace with actual column names
            ];
        }

        // Return the data as JSON
        echo json_encode(['success' => true, 'attendance' => $attendance]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No attendance records found.']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Employee ID not provided.']);
}
?>
