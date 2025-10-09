<?php
require 'php/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash password
    $type = $_POST['type'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, type, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $password, $type, $address, $phone);
    if ($stmt->execute()) {
        header("Location: login.php?status=success");
        exit();
    } else {
        $message = '<div class="message error">Error: Email already exists.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - FoodLink</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <style>
        :root {
    --primary: #28a745;
    --secondary: #6c757d;
    --bg: #f4f6f9;
    --white: #fff;
    --dark: #2d3436;
    --radius: 8px;
    --shadow: 0 4px 12px rgba(0,0,0,0.1);
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    background: var(--bg);
    color: var(--dark);
    line-height: 1.6;
    margin: 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 2rem;
}

/* Auth Forms */
.auth-container {
    max-width: 480px;
    margin: 4rem auto;
    padding: 2.5rem;
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    text-align: center;
}
.form-group {
    margin-bottom: 1.25rem;
    text-align: left;
}
.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}
.form-group input, .form-group textarea, .form-group select {
    width: 100%;
    padding: 12px;
    border-radius: var(--radius);
    border: 1px solid #ddd;
    box-sizing: border-box;
}
.btn {
    display: inline-block;
    padding: 12px 24px;
    border-radius: var(--radius);
    text-decoration: none;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-primary {
    background: var(--primary);
    color: var(--white);
    width: 100%;
}
.btn-primary:hover {
    background: #218838;
}

/* Dashboard */
header {
    background: var(--white);
    padding: 1rem 0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.logo { font-size: 1.5rem; font-weight: bold; color: var(--primary); }
.message {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: var(--radius);
    border: 1px solid transparent;
}
.success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
.error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }

/* Tables */
.data-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--white);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}
.data-table th, .data-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.data-table th {
    background: #f8f9fa;
}
    </style>
    <div class="auth-container">
        <h2>Join FoodLink</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="form-group"><label>Full Name</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" required></div>
            <div class="form-group"><label>Address</label><input type="text" name="address" required></div>
            <div class="form-group">
                <label>Account Type</label>
                <select name="type" required>
                    <option value="donor">Donor (Restaurant, Bakery, etc.)</option>
                    <option value="ngo">NGO (Charity, Shelter, etc.)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p style="margin-top: 1rem;">Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>