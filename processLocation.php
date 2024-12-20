<?php
session_start(); // Start the session

// Set the timezone to IST
date_default_timezone_set('Asia/Kolkata'); // Indian Standard Time

// Get the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Check if the latitude and longitude are set
if (!isset($data['latitude']) || !isset($data['longitude'])) {
    echo "Invalid data received.";
    exit;
}

$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Ensure userId is fetched from the session
if (!isset($_SESSION['userId'])) {
    echo "User not logged in. Attendance cannot be marked.";
    exit;
}

$userId = $_SESSION['userId'];

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'eams');

if ($conn->connect_error) {
    echo "Database connection failed: " . $conn->connect_error;
    exit;
}

// Fetch the allowed location (latitude and longitude) for the user from the database
$sql = "SELECT latitude, longitude FROM users WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found. Attendance cannot be marked.";
    exit;
}

$userData = $result->fetch_assoc();
$allowedLatitude = $userData['latitude'];
$allowedLongitude = $userData['longitude'];

// Example: Set your allowed radius (geofencing logic)
$radius = 0.5; // Allowed radius in kilometers

// Function to calculate the distance between two coordinates
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Earth's radius in kilometers

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
}

// Calculate the distance from the allowed location
$distance = calculateDistance($latitude, $longitude, $allowedLatitude, $allowedLongitude);

// Check if the user is within the geofence
if ($distance <= $radius) {
    // Dynamically create the table name based on userId
    $tableName = "user_" . $userId; // Each user has a table like "attendance_1", "attendance_2", etc.

    // Get the current date and time
    $date = date('Y-m-d');
    $day = date('D'); // Get the abbreviated day name (Mon, Tue, Wed, etc.)
    $time = date('H:i:s');

    // Check if attendance is already marked for today
    $checkSql = "SELECT * FROM `$tableName` WHERE date = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Attendance already marked for today
        echo "Attendance already marked for today.";
    } else {
        // Insert attendance record for today
        $status = 'Present'; // Since the user is within the geofence
        $insertSql = "INSERT INTO `$tableName` (date, day, time, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param('ssss', $date, $day, $time, $status);

        if ($stmt->execute()) {
            echo "Attendance marked successfully. You are within the geofence!";
        } else {
            echo "Error marking attendance: " . $stmt->error;
        }
    }
} else {
    echo "You are outside the geofence. Attendance not marked.";
}

$stmt->close();
$conn->close();
?>
