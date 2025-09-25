<?php
include "php/db.php";

if(isset($_POST['submit'])) {
    $food_item = $_POST['food_item'];
    $quantity = $_POST['quantity'];
    $pickup_time = $_POST['pickup_time'];
    $description = $_POST['description'];

    $sql = "INSERT INTO donations (food_item, quantity, pickup_time, description, status, created_at) 
            VALUES ('$food_item', '$quantity', '$pickup_time', '$description', 'Available', NOW())";

    if($conn->query($sql)) {
        echo "<script>alert('Donation posted successfully'); window.location='ngo_dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Post Donation - FoodLink</title>
<link rel="stylesheet" href="css/donor_dashboard.css">
</head>
<body>
<div class="container">
    <h2>Post New Donation</h2>
    <form method="POST">
        <label>Food Item:</label>
        <input type="text" name="food_item" required><br><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" required><br><br>

        <label>Pickup Time:</label>
        <input type="datetime-local" name="pickup_time" required><br><br>

        <label>Description:</label>
        <textarea name="description"></textarea><br><br>

        <button type="submit" name="submit" class="btn btn-primary">Post Donation</button>
    </form>
</div>
</body>
</html>
