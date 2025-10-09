<?php
session_start();
//donation
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the donation details from the form
    $food_name = $_POST['food_name'];
    $quantity = $_POST['quantity'];
    // ... other form fields

    // Step 1: Get the logged-in user's ID from the session
    $donor_id = $_SESSION['user_id'];

    // Step 2: Include the user's ID in the INSERT query
    $stmt = $conn->prepare(
        "INSERT INTO donations (food_name, quantity, donor_id) VALUES (?, ?, ?)"
    );
    
    // Step 3: Bind the user's ID along with the other data
    // The "i" in "ssi" stands for integer, for the donor_id
    $stmt->bind_param("ssi", $food_name, $quantity, $donor_id);
    
    // When this executes, the new donation is permanently linked to the user
    if ($stmt->execute()) {
        header("Location: donor_dashboard.php");
        exit();
    }
}

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
    <link rel="stylesheet" href="css/post_donation.css">

</head>
<body>
    <style>
        /* 🌐 Global Reset + Fonts */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  min-height: 100vh;
  background: linear-gradient(135deg, #00c6ff, #0072ff);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

/* 🔹 Form Card */
form {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(12px);
  padding: 2rem 2.5rem;
  border-radius: 18px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
  animation: slideUp 0.6s ease;
}

/* Title */
h2 {
  text-align: center;
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: #222;
  letter-spacing: 1px;
  position: relative;
}

h2::after {
  content: "";
  position: absolute;
  width: 50px;
  height: 3px;
  background: #0072ff;
  bottom: -8px;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 3px;
}

/* Labels */
label {
  font-weight: 600;
  color: #333;
  margin-bottom: 0.4rem;
  display: block;
  font-size: 0.95rem;
}

/* Inputs */
input,
textarea {
  width: 100%;
  padding: 0.85rem 1rem;
  margin-bottom: 1.2rem;
  border: 2px solid transparent;
  border-radius: 12px;
  background: #f1f5f9;
  font-size: 1rem;
  transition: all 0.3s ease;
}

input:focus,
textarea:focus {
  border-color: #0072ff;
  background: #fff;
  outline: none;
  box-shadow: 0 0 10px rgba(0, 114, 255, 0.2);
}

/* Textarea */
textarea {
  min-height: 100px;
  resize: none;
}

/* Submit Button */
button {
  width: 100%;
  padding: 0.95rem;
  border: none;
  border-radius: 12px;
  background: linear-gradient(135deg, #0072ff, #00c6ff);
  color: #fff;
  font-size: 1.05rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

button:hover {
  transform: translateY(-2px);
  background: linear-gradient(135deg, #00c6ff, #0072ff);
  box-shadow: 0 8px 20px rgba(0, 114, 255, 0.3);
}

/* ✨ Animation */
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* 🔹 Responsive */
@media (max-width: 600px) {
  form {
    padding: 1.5rem;
  }
  h2 {
    font-size: 1.6rem;
  }
}

    </style>
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


