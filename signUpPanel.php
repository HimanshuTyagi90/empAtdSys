<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: rgb(217, 213, 213);
        }

        .container {
            padding-top: 20px; /* Adjust to clear navbar */
            padding-bottom: 50px; /* Space from the bottom */
        }

        .vh-100-adjust {
            min-height: calc(100vh - 100px); /* Adjust height to exclude navbar */
        }
    </style>
</head>
<body>
<?php include '_partials/_navBar.php';?>

<div class="container d-flex align-items-center justify-content-center vh-100-adjust">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px; border-radius: 12px;">
        <h2 class="text-center mb-4">Creating Employee Account</h2>
        <form id="signupForm">
            <div class="mb-3">
                <label for="staticEmail" class="form-label">Email :</label>
                <input type="email" class="form-control" id="staticEmail" name="email" required placeholder="Email@example.com">
            </div>
            <div class="mb-3">
                <label for="inputPassword" class="form-label">Password :</label>
                <input type="password" class="form-control" id="inputPassword" name="password" required placeholder="Password here">
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name :</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required placeholder="Enter your first name here">
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name :</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required placeholder="Enter your last name here">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Contact No :</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your phone number here">
            </div>
            <div class="mb-3">
                <label for="latitudeCordinate" class="form-label">Latitude :</label>
                <input type="text" class="form-control" id="latitudeCordinate" name="latitude" required placeholder="Latitude co-ordinate assign to you here">
            </div>
            <div class="mb-3">
                <label for="longitudeCordinate" class="form-label">Longitude :</label>
                <input type="text" class="form-control" id="longitudeCordinate" name="longitude" required placeholder="Longitude co-ordinate assign to you here">
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-success w-100 fw-bold fs-5 mb-2">Create Account</button>
                <button type="reset" class="btn btn-danger w-100 fw-bold fs-5">Reset</button>
            </div>
            <div class="text-center">
                <a href="index.php" class="text-success fw-bold" style="text-decoration: none;">
                    Login as Employee
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('signupForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    // Get form data
    const formData = {
        email: document.getElementById('staticEmail').value,
        password: document.getElementById('inputPassword').value,
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        phone: document.getElementById('phone').value,
        latitude: document.getElementById('latitudeCordinate').value,
        longitude: document.getElementById('longitudeCordinate').value
    };

    // Send data to PHP server via fetch
    fetch('processUserSignUp.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(response => response.text())  // Parse the response as text first
    .then(data => {
        try {
            const parsedData = JSON.parse(data); // Try parsing the response as JSON
            if (parsedData.success) {
                // Save userId to localStorage
                localStorage.setItem('userId', parsedData.userId);
                alert('Account created successfully!');
                window.location.href = 'index.php'; // Redirect to login page
            } else {
                alert('Error: ' + parsedData.message); // Handle error message
            }
        } catch (e) {
            console.error('Error parsing JSON:', data);  // If JSON parsing fails, log the raw data
            alert('There was an error with the server response.');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('There was an error creating the account.'); // Handle fetch errors
    });
});

</script>

</body>
</html>
