<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History - FoodLink</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/donor_dashboard.css">
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            border: 1px solid #c3e6cb;
            border-radius: var(--radius);
            margin-bottom: 1rem;
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

    <section class="dashboard">
        <div class="container">
            <div class="page-header">
                <h2 class="section-title" style="margin-bottom: 0;">Donation History</h2>
                <a href="post_donation.php" class="btn btn-primary"><i class="fas fa-plus"></i> Post New Donation</a>
            </div>

            <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> Donation updated successfully!
                </div>
            <?php endif; ?>

            <table class="donations-table">
                <thead>
                    <tr>
                        <th>Food Item</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Date Posted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM donations ORDER BY created_at DESC";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_class = "status-available";
                            if ($row['status'] == "Claimed") $status_class = "status-claimed";
                            if ($row['status'] == "Completed") $status_class = "status-completed";

                            echo "
                            <tr>
                                <td>" . htmlspecialchars($row['food_item']) . "</td>
                                <td>" . htmlspecialchars($row['quantity']) . "</td>
                                <td><span class='status-badge $status_class'>" . htmlspecialchars($row['status']) . "</span></td>
                                <td>" . date("M d, Y, g:i A", strtotime($row['created_at'])) . "</td>
                                <td>
                                    <div class='card-actions'>
                                        <a href='edit_donation.php?id={$row['id']}' class='btn btn-outline'><i class='fas fa-edit'></i> Edit</a>
                                        <form action='delete_post.php' method='POST' onsubmit=\"return confirm('Are you sure?');\">
                                            <input type='hidden' name='donation_id' value='{$row['id']}'>
                                            <button type='submit' name='delete_post' class='btn btn-danger'><i class='fas fa-trash'></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-muted'>No donations found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>