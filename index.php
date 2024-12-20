<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <title>HomePage</title>
</head>
<body style="background-color: rgb(217, 213, 213);">
    <?php include '_partials/_navBar.php'; ?>

    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px; border-radius: 12px;">
            <h2 class="text-center mb-4">Log In as Employee</h2>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="staticEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="staticEmail" name="email" placeholder="employee_email@example.com" required>
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputPassword" name="password" placeholder="employee password here" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary w-100 fw-bold fs-5 mb-2">Login</button>
                    <button type="reset" class="btn btn-danger w-100 fw-bold fs-5">Reset</button>
                </div>
                <div class="text-center">
                    <a href="signUpPanel.php" class="text-success fw-bold" style="text-decoration: none;">
                        Create New Account
                    </a>
                </div>
                <p id="loginStatus" class="text-center mt-3 text-danger fw-bold"></p>
            </form>
        </div>
    </div>

    <script>
         document.getElementById('loginForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            const email = document.getElementById('staticEmail').value;
            const password = document.getElementById('inputPassword').value;

            // Send login data to the server
            const response = await fetch('processUserLogin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.text();  // Use response.text() for debugging

            try {
                const result = JSON.parse(data);

                if (result.success) {
                    // Store userId in localStorage
                    localStorage.setItem('userId', result.userId);
                    window.location.href = 'attendanceMarking.php'; // Redirect to attendance page
                } else {
                    document.getElementById('loginStatus').textContent = result.message;
                }
            } catch (e) {
                console.error('Error parsing JSON:', data);
                alert('An error occurred: ' + data); // Handle errors if JSON parsing fails
            }
        });
    </script>
</body>
</html>
