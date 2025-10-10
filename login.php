<?php
// In login.php
// Handles login for all user types.

session_start();
require 'php/db.php'; // Assuming db.php is in a 'php' subfolder
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Uses prepared statement to prevent SQL injection.
    $stmt = $conn->prepare("SELECT id, name, password, type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verifies the hashed password.
        if (password_verify($password, $user['password'])) {
            // Set session variables upon successful login.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_type'] = $user['type'];

            // Redirect based on user type.
            if ($user['type'] == 'donor') {
                header("Location: donor_dashboard.php");
            } else {
                header("Location: ngo_dashboard.php"); // Assuming you have this page
            }
            exit();
        }
    }
    $message = '<div class="message error">Invalid email or password.</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - FoodLink</title>
    <link rel="stylesheet" href="css/donor_login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Login to FoodLink</h2>
            <?php if(isset($_GET['status']) && $_GET['status'] == 'success') echo '<div class="message success">Registration successful! Please log in.</div>'; ?>
            <?php echo $message; ?>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <p class="register-link">Don't have an account? <a href="donor_register.html">Register here</a></p>
        </div>
    </div>
</body>
</html>