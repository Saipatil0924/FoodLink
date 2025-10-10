<?php
include "db.php"; // Database connection

// Use a static donor ID (ensure this user exists in your users table)
$donor_id = 15;

// Initialize message
$message = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate required fields
    if (empty($_POST['food_name']) || empty($_POST['quantity']) || empty($_POST['expiry_time'])) {
        $message = "Food Name, Quantity, and Expiry Time are required!";
    } else {
        // Sanitize input
        $food_name   = trim($_POST['food_name']);
        $quantity    = trim($_POST['quantity']);
        $description = trim($_POST['description']);
        $expiry_time = str_replace("T", " ", trim($_POST['expiry_time'])); // Convert datetime-local to MySQL format
        $location    = trim($_POST['location']);
        $status      = 'available';
        $ngo_id      = NULL;

        // Handle photo upload
        $photo_url = NULL;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $fileName = time() . "_" . basename($_FILES['photo']['name']);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $photo_url = $targetFile;
            }
        }

        // Prepare SQL insert
        $stmt = $conn->prepare("
            INSERT INTO donations 
            (food_name, quantity, description, photo_url, expiry_time, donor_id, ngo_id, location, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssiiss",
            $food_name,
            $quantity,
            $description,
            $photo_url,
            $expiry_time,
            $donor_id,
            $ngo_id,
            $location,
            $status
        );

        if ($stmt->execute()) {
            $message = "Donation posted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Post Donation</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:"Poppins",sans-serif;}
body{min-height:100vh;background:linear-gradient(135deg,#00c6ff,#0072ff);display:flex;align-items:center;justify-content:center;padding:2rem;}
form{background:rgba(255,255,255,0.9);backdrop-filter:blur(12px);padding:2rem 2.5rem;border-radius:18px;width:100%;max-width:500px;box-shadow:0 12px 30px rgba(0,0,0,0.15);}
h2{text-align:center;font-size:2rem;font-weight:700;margin-bottom:1.5rem;color:#222;letter-spacing:1px;position:relative;}
h2::after{content:"";position:absolute;width:50px;height:3px;background:#0072ff;bottom:-8px;left:50%;transform:translateX(-50%);border-radius:3px;}
input,textarea{width:100%;padding:0.85rem 1rem;margin-bottom:1.2rem;border:2px solid transparent;border-radius:12px;background:#f1f5f9;font-size:1rem;transition:all 0.3s ease;}
input:focus,textarea:focus{border-color:#0072ff;background:#fff;outline:none;box-shadow:0 0 10px rgba(0,114,255,0.2);}
textarea{min-height:100px;resize:none;}
button{width:100%;padding:0.95rem;border:none;border-radius:12px;background:linear-gradient(135deg,#0072ff,#00c6ff);color:#fff;font-size:1.05rem;font-weight:600;cursor:pointer;transition:all 0.3s ease;text-transform:uppercase;letter-spacing:0.5px;}
button:hover{transform:translateY(-2px);background:linear-gradient(135deg,#00c6ff,#0072ff);box-shadow:0 8px 20px rgba(0,114,255,0.3);}
.message{text-align:center;margin-bottom:1rem;font-weight:600;color:#0072ff;}
</style>
</head>
<body>

<form action="post_donation.php" method="POST" enctype="multipart/form-data">
    <h2>Post Donation</h2>
    <?php if (!empty($message)) echo "<div class='message'>{$message}</div>"; ?>
    <input type="text" name="food_name" placeholder="Food Name" required>
    <input type="text" name="quantity" placeholder="Quantity" required>
    <textarea name="description" placeholder="Description"></textarea>
    <input type="datetime-local" name="expiry_time" required>
    <input type="text" name="location" placeholder="Location">
    <input type="file" name="photo">
    <button type="submit">Post Donation</button>
</form>

</body>
</html>
