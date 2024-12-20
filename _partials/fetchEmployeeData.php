<?php
// Start the session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access Denied!'); window.location.href='adminLogInPanel.php';</script>";
    exit;
}

// Check if 'empid' is passed in the query string
if (isset($_GET['empid'])) {
    $empid = $_GET['empid'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'eams');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL to fetch the employee data by userId (empid)
    $sql = "SELECT userId, firstName, lastName, phone, longitude, latitude FROM users WHERE userId = ?";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter (empid)
        $stmt->bind_param('i', $empid);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if data exists
        if ($result->num_rows > 0) {
            $employee = $result->fetch_assoc();

            // Return data as JSON
            echo json_encode([
                'success' => true,
                'employee' => $employee
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Employee not found'
            ]);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error preparing query: ' . $conn->error
        ]);
    }

    // Close the connection
    $conn->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Employee ID not provided'
    ]);
}
?>
