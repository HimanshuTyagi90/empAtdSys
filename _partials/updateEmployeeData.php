<?php
session_start(); // Start the session

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access Denied!'); window.location.href='adminLogInPanel.php';</script>";
    exit;
}

// Check if the POST request has been made with employee data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data (employee info)
    $data = json_decode(file_get_contents('php://input'), true);

    $empid = $data['empid'];
    $name = $data['name'];
    $contact = $data['contact'];
    $longitude = $data['longitude'];
    $latitude = $data['latitude'];

    // Validate the data
    if (empty($empid) || empty($name) || empty($contact)) {
        echo "All fields are required!";
        exit;
    }

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'eams');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query to update employee data
    $sql = "UPDATE users SET 
                firstName = ?, 
                lastName = ?, 
                phone = ?, 
                longitude = ?, 
                latitude = ? 
            WHERE userId = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Split the name into first name and last name
        list($firstName, $lastName) = explode(' ', $name, 2);

        // Bind the parameters
        $stmt->bind_param('ssssdi', $firstName, $lastName, $contact, $longitude, $latitude, $empid);

        // Execute the query
        if ($stmt->execute()) {
            echo "Employee data updated successfully!";
        } else {
            echo "Error updating employee data: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    echo "Invalid request method!";
}
?>
