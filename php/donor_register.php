<?php
// donor_register.php
session_start();
include "db.php"; // ensure you have db.php with $conn connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../donor_register.html");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: ../donor_register.html");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../donor_register.html");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM donors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email already registered.";
        $stmt->close();
        header("Location: ../donor_register.html");
        exit();
    }
    $stmt->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert donor
    $stmt = $conn->prepare("INSERT INTO donors (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. Please login.";
        header("Location: ../donor_login.html");
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: ../donor_register.html");
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../donor_register.html");
    exit();
}
