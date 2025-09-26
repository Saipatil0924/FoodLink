<?php

include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $food_item = $_POST['food_item'];
    $quantity = $_POST['quantity'];
    $pickup_time = $_POST['pickup_time'];
    $description = $_POST['description'];
    $status = "Available";

    $stmt = $conn->prepare("INSERT INTO donations (food_item, quantity, pickup_time, description, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sisss", $food_item, $quantity, $pickup_time, $description, $status);

    if ($stmt->execute()) {
        echo "Donation posted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Donation</title>
</head>
<body>
    <h2>Post a New Donation</h2>
    <form method="POST">
        <label>Food Item:</label><br>
        <input type="text" name="food_item" required><br><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" required><br><br>

        <label>Pickup Time:</label><br>
        <input type="datetime-local" name="pickup_time" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required></textarea><br><br>

        <button type="submit">Post Donation</button>
    </form>
</body>
</html>
=======
include 'db.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_id = 1; // TODO: Replace with logged-in donor's ID from session
    $food_title = $_POST['food_title'];
    $quantity = $_POST['quantity'];
    $pickup_time = $_POST['pickup_time'];
    $description = $_POST['description'];

    $sql = "INSERT INTO donations (donor_id, food_title, quantity, pickup_time, description) 
            VALUES ('$donor_id', '$food_title', '$quantity', '$pickup_time', '$description')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../donor_dashboard.html?success=1");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
>>>>>>> bd2bbe688a7d45e779659a5818d5d8a65e981e70
