<?php
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
