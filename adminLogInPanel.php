<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <title>Admin LogIn</title>
</head>

<body>
    <?php include '_partials/_navBar.php'; ?>
    <div class="container d-flex align-items-center justify-content-center vh-100 mt-10">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px; border-radius: 12px;">
            <h2 class="text-center mb-4">Log In as Admin</h2>
            <form id="adminLoginForm">
                <div class="mb-3">
                    <label for="staticEmail" class="form-label">Admin Email :</label>
                    <input type="email" class="form-control" id="staticEmail" name="email" placeholder="Admin_email@example.com">
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Admin Password :</label>
                    <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Admin password here">
                </div>
                <div class="d-grid mb-3">
                    <!-- <button type="submit" class="btn btn-primary w-100 fw-bold fs-5 mb-2">Login</button> -->
                    <button type="submit" class="btn btn-primary w-100 fw-bold fs-5 mb-2">Login as Admin</button>
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
       document.getElementById('adminLoginForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission

    // Get form data
    const email = document.getElementById('staticEmail').value;
    const password = document.getElementById('inputPassword').value;

    // Send data to PHP server via fetch
    fetch('processAdminLogin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    })
        .then(response => response.text()) // Change from response.json() to response.text()
        .then(data => {
            try {
                const jsonData = JSON.parse(data);
                if (jsonData.success) {
                    alert(jsonData.message);
                    window.location.href = 'adminDashboard.php'; // Redirect to admin dashboard
                } else {
                    alert(jsonData.message);
                }
            } catch (e) {
                console.error('Error parsing JSON:', data); // Log the response for debugging
                alert('Unexpected server response. Please check the console for details.');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('There was an error logging in. Please try again.');
        });
});

    </script>
</body>

</html>