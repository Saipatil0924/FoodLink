<?php
session_start();
include "db.php";

// ✅ Run only once per POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs safely
    $food_item   = $_POST['food_item'];
    $quantity    = $_POST['quantity'];
    $pickup_time = $_POST['pickup_time'];
    $description = $_POST['description'];
    $status      = "Available";

    // Get logged-in user ID
    $donor_id = $_SESSION['user_id'];

    // ✅ Prepare a single, correct INSERT query
    $stmt = $conn->prepare("INSERT INTO donations (food_item, quantity, pickup_time, description, status, donor_id, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bind_param("sisssi", $food_item, $quantity, $pickup_time, $description, $status, $donor_id);

    if ($stmt->execute()) {
        // redirect or message
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
    <link rel="stylesheet" href="css/post_donation.css">
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

        label {
          font-weight: 600;
          color: #333;
          margin-bottom: 0.4rem;
          display: block;
          font-size: 0.95rem;
        }

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

        textarea {
          min-height: 100px;
          resize: none;
        }

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

        @media (max-width: 600px) {
          form {
            padding: 1.5rem;
          }
          h2 {
            font-size: 1.6rem;
          }
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Post a New Donation</h2>

        <label>Food Item:</label>
        <input type="text" name="food_item" required>

        <label>Quantity:</label>
        <input type="number" name="quantity" required>

        <label>Pickup Time:</label>
        <input type="datetime-local" name="pickup_time" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <button type="submit">Post Donation</button>
    </form>
</body>
</html>
