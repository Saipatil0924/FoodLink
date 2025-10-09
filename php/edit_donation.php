<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db.php";

$donation_id = null;
$donation = null;

// --- Part 1: Handle the form submission (POST request) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_donation'])) {
    // Sanitize and retrieve form data
    $donation_id = $_POST['donation_id'];
    $food_item = $conn->real_escape_string($_POST['food_item']);
    $quantity = (int)$_POST['quantity'];
    $pickup_time = $conn->real_escape_string($_POST['pickup_time']);
    $description = $conn->real_escape_string($_POST['description']);
    $status = $conn->real_escape_string($_POST['status']);

    // Prepare the UPDATE statement
    $sql = "UPDATE donations SET 
                food_item = ?, 
                quantity = ?, 
                pickup_time = ?, 
                description = ?, 
                status = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("sisssi", $food_item, $quantity, $pickup_time, $description, $status, $donation_id);
    
    if ($stmt->execute()) {
        // Redirect back to the history page with a success message
        header("Location: donation_history.php?status=success");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}

// --- Part 2: Fetch existing data for the form (GET request) ---
if (isset($_GET['id'])) {
    $donation_id = (int)$_GET['id'];
    $sql = "SELECT * FROM donations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $donation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $donation = $result->fetch_assoc();
    } else {
        die("Donation not found.");
    }
    $stmt->close();
} else {
    die("No donation ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Donation - FoodLink</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/donor_dashboard.css">
    <style>
        /* Styles for the edit form page */
        .form-container {
            max-width: 700px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-family: "Poppins", sans-serif;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo"><i class="fas fa-utensils"></i> <span>FoodLink</span></div>
                <nav>
                    <ul>
                        <li><a href="../donor_dashboard.php">Dashboard</a></li>
                        <li><a href="donation_history.php">Donation History</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2><i class="fas fa-edit"></i> Edit Donation Details</h2>
        <hr style="margin: 1rem 0 2rem;">

        <?php if ($donation): ?>
        <form action="edit_donation.php" method="POST">
            <input type="hidden" name="donation_id" value="<?php echo htmlspecialchars($donation['id']); ?>">

            <div class="form-group">
                <label for="food_item">Food Item / Meal Name</label>
                <input type="text" id="food_item" name="food_item" value="<?php echo htmlspecialchars($donation['food_item']); ?>" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity (Number of meals)</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($donation['quantity']); ?>" required>
            </div>

            <div class="form-group">
                <label for="pickup_time">Pickup By (Time)</label>
                <input type="time" id="pickup_time" name="pickup_time" value="<?php echo htmlspecialchars($donation['pickup_time']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Available" <?php if ($donation['status'] == 'Available') echo 'selected'; ?>>Available</option>
                    <option value="Claimed" <?php if ($donation['status'] == 'Claimed') echo 'selected'; ?>>Claimed</option>
                    <option value="Completed" <?php if ($donation['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($donation['description']); ?></textarea>
            </div>

            <div class="form-actions">
                <a href="donation_history.php" class="btn btn-outline">Cancel</a>
                <button type="submit" name="update_donation" class="btn btn-primary">Update Donation</button>
            </div>
        </form>
        <?php else: ?>
            <p>Could not load donation data.</p>
        <?php endif; ?>
    </div>
</body>
</html>