<?php
session_start(); // Start the session
// Check if the device is mobile
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/Mobile|Android|iP(hone|ad|od)|BlackBerry|IEMobile|Silk/', $userAgent)) {
    // Check if user is logged in (userId stored in localStorage)
    if (!isset($_SESSION['userId'])) {
        echo "<script>
                alert('You are not logged in. Redirecting to login screen.');
                window.location.href = 'index.php'; // Redirect to login page if not logged in
              </script>";
        exit;
    }

    // If logged in, continue to the attendance page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <title>Attendance System</title>
    <style>
        body {
            background-color: rgb(217, 213, 213) !important; /* Force the background color */
            margin: 0; /* Remove default margins */
        }

        .button-container {
            margin-top: 100px; /* Add space below the navigation bar */
        }

        .status {
            margin-left: 20px;
        }
    </style>
</head>
<body>

<?php 
include '_partials/_navBar.php'; 
?>



<div class="d-grid gap-2 col-8 mx-auto button-container">
    <button class="btn btn-primary" id="getLocation" type="button">Mark Attendance</button>
</div>

<div class="d-grid gap-2 col-10 mx-auto mt-2 status">
    <h3 id="status"></h3>
</div>

<!-- table area below -->

<table class="table" id="attendanceTable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">Day</th>
            <th scope="col">Time</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
        <!-- Attendance data will be inserted here by JavaScript -->
    </tbody>
</table>

<script>
       // Fetch attendance data
       window.onload = function() {
        // Check if the userId is available in session or localStorage
        const userId = '<?php echo $_SESSION["userId"]; ?>';
        if (!userId) {
            alert('You are not logged in. Redirecting to login screen.');
            window.location.href = 'index.php'; // Redirect to index.php if not logged in
        }

        fetch('fetchAttendance.php', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tableBody = document.querySelector('#attendanceTable tbody');
                data.data.forEach((row, index) => {
                    const statusClass = row.status === 'Present' ? 'text-success' : 'text-danger';
                    const rowHTML = `
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td>${row.date}</td>
                            <td>${row.day}</td>
                            <td>${row.time}</td>
                            <td class="${statusClass}">${row.status}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += rowHTML;
                });
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching attendance data:', error);
        });
    };

    // Handle location-based attendance marking
    document.getElementById('getLocation').addEventListener('click', () => {
        const statusElement = document.getElementById('status');

        if (!navigator.geolocation) {
            statusElement.textContent = 'Geolocation is not supported by your browser.';
            return;
        }

        statusElement.textContent = 'Requesting location...';
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                statusElement.textContent = `Location: Latitude ${latitude}, Longitude ${longitude}`;

                // Send the location to SERVER
                fetch('processLocation.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ latitude, longitude })
                })
                .then(response => response.text())
                .then(data => {
                    statusElement.textContent = `Server Response: ${data}`;

                     // If attendance is marked successfully, refresh the table
                if (data.includes('Attendance marked successfully')) {
                    // Reload the current page reloads the table data
                    location.reload();
                    
                } else {
                    console.error('Attendance not marked:', data);
                }

                })
                .catch(err => {
                    statusElement.textContent = `Error: ${err.message}`;
                });
            },
            (error) => {
                statusElement.textContent = `Error: ${error.message}`;
            }
        );
    });

    
</script>
</body>
</html>

<?php
} else {
  //  // Deny access for non-mobile devices
    echo "Access denied: This system is accessible only from mobile devices.";
    exit;
}
?>
