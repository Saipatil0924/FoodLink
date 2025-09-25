<?php
include "db.php"; // your DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $food_item = $_POST['food_item'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $pickup_time = $_POST['pickup_time'];

    $sql = "INSERT INTO donations (food_item, quantity, description, pickup_time) 
            VALUES ('$food_item', '$quantity', '$description', '$pickup_time')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../donor_dashboard.html?success=1");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
