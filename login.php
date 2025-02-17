<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href=".css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p id="error-message" class="error"></p>
        <p>Don't have an account? <a href="#">Register here</a></p>
    </div>

    <script>document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form from submitting

    // Get input values
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Simple validation (you can replace this with actual backend logic)
    if (username === "admin" && password === "password") {
        alert("Login successful!");
        // Redirect to another page or perform other actions
        window.location.href = "dashboard.html"; // Example redirect
    } else {
        document.getElementById('error-message').textContent = "Invalid username or password";
    }
});<script>
</body>
</html>