<?php
// In donor_register.php
// Handles new donor registration.

session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // --- Validation ---
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../donor_register.html");
        exit();
    }
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../donor_register.html");
        exit();
    }

    // --- Check if user already exists in the 'users' table ---
    // CORRECTED: Checks the correct 'users' table, not 'donors'.
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
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

    // --- Hash password and insert into the 'users' table ---
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $user_type = 'donor'; // Set the user type automatically for this form.

    // CORRECTED: Inserts into the correct 'users' table with the 'type' column.
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $user_type);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: ../donor_login.html");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: ../donor_register.html");
        exit();
    }
    $stmt->close();
    $conn->close();
}
?>