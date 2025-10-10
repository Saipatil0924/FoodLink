<?php
// In php/post_donation.php
// Securely adds a new donation to the database.

session_start();
include "db.php";

// SECURITY: Redirect user if they are not logged in.
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CORRECTED: Uses column names from foodlink.sql (food_name, expiry_time).
    $food_name   = $_POST['food_name'];
    $quantity    = $_POST['quantity'];
    $expiry_time = $_POST['expiry_time'];
    $description = $_POST['description'];
    $location    = $_POST['location']; // Assuming you add this field to your form.
    $status      = "available"; // Status from foodlink.sql schema
    $donor_id    = $_SESSION['user_id'];

    // SECURITY: Uses a prepared statement to prevent SQL injection.
    $stmt = $conn->prepare(
        "INSERT INTO donations (food_name, quantity, expiry_time, description, location, status, donor_id, created_at) 
         VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
    );
    $stmt->bind_param("ssssssi", $food_name, $quantity, $expiry_time, $description, $location, $status, $donor_id);

    if ($stmt->execute()) {
        header("Location: ../donor_dashboard.php?status=posted");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post a New Donation</title>
    <link rel="stylesheet" href="../css/post_donation.css"> </head>
<body>
    <form method="POST" action="post_donation.php">
        <h2>Post a New Donation</h2>

        <label for="food_name">Food Name:</label>
        <input type="text" id="food_name" name="food_name" placeholder="e.g., Vegetable Biryani" required>

        <label for="quantity">Quantity:</label>
        <input type="text" id="quantity" name="quantity" placeholder="e.g., 20 portions or 5 kg" required>

        <label for="expiry_time">Best Before:</label>
        <input type="datetime-local" id="expiry_time" name="expiry_time" required>

        <label for="location">Pickup Location:</label>
        <input type="text" id="location" name="location" placeholder="e.g., Downtown Diner, 123 Main St" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" placeholder="Any extra details about the food" required></textarea>

        <button type="submit">Post Donation</button>
    </form>
</body>
</html>