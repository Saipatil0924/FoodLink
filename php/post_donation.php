<?php
session_start();
include "db.php";

// Ensure user is logged in

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $food_name    = trim($_POST['food_name']);
    $quantity     = trim($_POST['quantity']);
    $pickup_time  = trim($_POST['pickup_time']); // format: YYYY-MM-DDTHH:MM
    $description  = trim($_POST['description']);
    $status       = "Available";
    $donor_id     = $_SESSION['user_id'];

    // Convert datetime-local to MySQL DATETIME
    $pickup_time = str_replace("T", " ", $pickup_time);

    // Optional: handle photo upload
    $photo_url = NULL;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['photo']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $photo_url = $targetFile;
        }
    }

    // Insert donation
    $stmt = $conn->prepare("INSERT INTO donations 
        (food_name, quantity, pickup_time, description, status, donor_id, photo_url, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param("sisssis", 
        $food_name, 
        $quantity, 
        $pickup_time, 
        $description, 
        $status, 
        $donor_id, 
        $photo_url
    );

    if ($stmt->execute()) {
        header("Location: ../donor_dashboard.php");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Post Donation</title>
<link rel="stylesheet" href="../css/post_donation.css">
<style>
body {
    font-family: "Poppins", sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}
form {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(12px);
    padding: 2rem 2.5rem;
    border-radius: 18px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}
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
    content:"";
    position:absolute;
    width:50px;
    height:3px;
    background:#0072ff;
    bottom:-8px;
    left:50%;
    transform:translateX(-50%);
    border-radius:3px;
}
input,textarea {
    width:100%;
    padding:0.85rem 1rem;
    margin-bottom:1.2rem;
    border:2px solid transparent;
    border-radius:12px;
    background:#f1f5f9;
    font-size:1rem;
    transition:all 0.3s ease;
}
input:focus,textarea:focus {
    border-color:#0072ff;
    background:#fff;
    outline:none;
    box-shadow:0 0 10px rgba(0,114,255,0.2);
}
textarea { min-height:100px; resize:none; }
button {
    width:100%;
    padding:0.95rem;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#0072ff,#00c6ff);
    color:#fff;
    font-size:1.05rem;
    font-weight:600;
    cursor:pointer;
    text-transform:uppercase;
    letter-spacing:0.5px;
}
button:hover { transform:translateY(-2px); background:linear-gradient(135deg,#00c6ff,#0072ff); }
</style>
</head>
<body>
<form method="POST" enctype="multipart/form-data">
    <h2>Post a New Donation</h2>

    <label>Food Name:</label>
    <input type="text" name="food_name" required>

    <label>Quantity:</label>
    <input type="number" name="quantity" required>

    <label>Pickup Time:</label>
    <input type="datetime-local" name="pickup_time" required>

    <label>Description:</label>
    <textarea name="description" required></textarea>

    <label>Photo (optional):</label>
    <input type="file" name="photo">

    <button type="submit">Post Donation</button>
</form>
</body>
</html>
