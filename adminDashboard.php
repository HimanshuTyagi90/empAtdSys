<?php
session_start(); // Start the session

// Check if admin is logged in 
if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access Denied!'); window.location.href='adminLogInPanel.php';</script>";
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'eams');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total number of employees
$sql = "SELECT COUNT(*) AS totalEmployees FROM users";
$result = $conn->query($sql);
$totalEmployees = $result->fetch_assoc()['totalEmployees'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <title>Admin Dashboard</title>
    <style>
        body {
            background-color: rgb(217, 213, 213);
        }

        .container {
            margin-top: 50px;
        }

        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 100%;
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <?php include '_partials/_navBar.php'; ?>

    <!-- Dashboard layout -->
    <div class="container">
        <h2>Admin Dashboard</h2>

        <!-- Total Employees Card -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Employees</h5>
                <p class="card-text"><?php echo $totalEmployees; ?> Employees</p>
            </div>
        </div>

        <!-- View and Edit Employees Button -->
        <div class="mt-3">
            <button class="btn btn-primary w-100 py-3" id="viewEmployeesBtn" data-bs-toggle="modal" data-bs-target="#employeeModal">View & Edit Employee Data</button>
        </div>
        
        <!-- View Employee Attendance Button -->
        <div class="mt-3">
            <button class="btn btn-secondary w-100 py-3" id="viewAttendanceBtn" data-bs-toggle="modal" data-bs-target="#attendanceModal">View Employee Attendance</button>
        </div>

    </div>



    <!-- Modal to show Employee List (View Data) -->
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">Employee Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="employeeTable">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch all employees
                                $sql = "SELECT userId, CONCAT(firstName, ' ', lastName) AS fullName, phone FROM users";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['userId']}</td>
                                                <td>{$row['fullName']}</td>
                                                <td>{$row['phone']}</td>
                                                <td>
                                                    <button class='btn btn-info btn-sm' onclick='viewEmployee({$row['userId']})'>View Data</button>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No employees found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Employee Data -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Employee Data</h5>
                    <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm">
                        <input type="hidden" id="editEmpId">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editContact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="editContact" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editLongitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="editLongitude" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editLatitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="editLatitude" readonly>
                        </div>
                        <button type="button" class="btn btn-primary w-100" id="editBtn" onclick="enableEdit()">Edit</button>
                        <button type="submit" class="btn btn-success w-100" id="updateBtn" style="display:none;">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to show Employee Attendance Data -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Employee Attendance Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th scope="col">Sr. No.</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Day</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                                <!-- Attendance data will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Function to view employee data in the edit modal
        function viewEmployee(empid) {
            fetch(`_partials/fetchEmployeeData.php?empid=${empid}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Fill form with employee data
                        document.getElementById('editEmpId').value = data.employee.userId;
                        document.getElementById('editName').value = data.employee.firstName + ' ' + data.employee.lastName;
                        document.getElementById('editContact').value = data.employee.phone;
                        document.getElementById('editLongitude').value = data.employee.longitude;
                        document.getElementById('editLatitude').value = data.employee.latitude;

                        // Show the modal
                        new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
                    } else {
                        alert('Failed to fetch employee data');
                    }
                });
        }

        // Function to make form editable when "Edit" button is clicked
        function enableEdit() {
            document.getElementById('editName').readOnly = false;
            document.getElementById('editContact').readOnly = false;
            document.getElementById('editLongitude').readOnly = false;
            document.getElementById('editLatitude').readOnly = false;
            
            // Show Update button and hide Edit button
            document.getElementById('editBtn').style.display = 'none';
            document.getElementById('updateBtn').style.display = 'inline-block';
        }

        // Function to view employee attendance data in the attendance modal
function viewAttendance(empid) {
    fetch(`_partials/fetchEmployeeAttendance.php?empid=${empid}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear previous data
                const attendanceTableBody = document.getElementById('attendanceTableBody');
                attendanceTableBody.innerHTML = '';

                // Populate the table with employee's attendance data
                data.attendance.forEach((attendance, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${attendance.date}</td>
                        <td>${attendance.day}</td>
                        <td>${attendance.time}</td>
                        <td>${attendance.status}</td>
                    `;
                    attendanceTableBody.appendChild(row);
                });

                // Show the attendance modal
                new bootstrap.Modal(document.getElementById('attendanceModal')).show();
            } else {
                alert('Failed to fetch attendance data');
            }
        });
}

    // Modify the employee row's action button to call the viewAttendance function
    function viewEmployee(empid) {
        fetch(`_partials/fetchEmployeeData.php?empid=${empid}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Fill form with employee data
                    document.getElementById('editEmpId').value = data.employee.userId;
                    document.getElementById('editName').value = data.employee.firstName + ' ' + data.employee.lastName;
                    document.getElementById('editContact').value = data.employee.phone;
                    document.getElementById('editLongitude').value = data.employee.longitude;
                    document.getElementById('editLatitude').value = data.employee.latitude;

                    // Show the modal
                    new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
                } else {
                    alert('Failed to fetch employee data');
                }
            });

        // Update the view attendance button in the modal
        const viewAttendanceBtn = document.getElementById('viewAttendanceBtn');
        viewAttendanceBtn.onclick = function () {
            viewAttendance(empid);
        };
    }

    // Modify the employee row's action button to call the viewAttendance function
    function viewEmployee(empid) {
        fetch(`_partials/fetchEmployeeData.php?empid=${empid}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Fill form with employee data
                    document.getElementById('editEmpId').value = data.employee.userId;
                    document.getElementById('editName').value = data.employee.firstName + ' ' + data.employee.lastName;
                    document.getElementById('editContact').value = data.employee.phone;
                    document.getElementById('editLongitude').value = data.employee.longitude;
                    document.getElementById('editLatitude').value = data.employee.latitude;

                    // Show the modal
                    new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
                } else {
                    alert('Failed to fetch employee data');
                }
            });

        // Update the view attendance button in the modal
        const viewAttendanceBtn = document.getElementById('viewAttendanceBtn');
        viewAttendanceBtn.onclick = function () {
            viewAttendance(empid);
        };
    }


        // Function to handle form submission for updating employee data
        document.getElementById('editEmployeeForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const empid = document.getElementById('editEmpId').value;
            const name = document.getElementById('editName').value;
            const contact = document.getElementById('editContact').value;
            const longitude = document.getElementById('editLongitude').value;
            const latitude = document.getElementById('editLatitude').value;

            fetch('_partials/updateEmployeeData.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ empid, name, contact, longitude, latitude })
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data.includes('success')) {
                    location.reload(); // Reload the page to see updated data
                }
            });
        });
    </script>

</body>

</html>

<?php $conn->close(); ?>
