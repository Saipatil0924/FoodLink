<?php
session_start();
require 'php/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_type'] = $user['type'];

            // Redirect based on user type
            if ($user['type'] == 'donor') {
                header("Location: donor_dashboard.php");
            } else {
                header("Location: ngo_dashboard.php");
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Login to FoodLink</h2>
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success') echo '<div class="message success">Registration successful! Please log in.</div>'; ?>
        <?php echo $message; ?>
        <form method="POST">
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p style="margin-top: 1rem;">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>