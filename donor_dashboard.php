<?php
// Database connection
include "php/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - FoodLink</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-utensils"></i>
                    <span>FoodLink</span>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Donations</a></li>
                        <li><a href="#">Impact</a></li>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <a href="#" class="btn btn-outline">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Dashboard Section -->
    <section class="dashboard">
        <div class="container">
            <h2 class="section-title">Donor Dashboard</h2>
            
            <div class="dashboard-content">
                <!-- Sidebar -->
                <div class="dashboard-sidebar">
                    <div class="sidebar-menu">
                        <a href="#" class="active"><i class="fas fa-home"></i> Overview</a>
                        <a href="#"><i class="fas fa-plus-circle"></i> Post Donation</a>
                        <a href="#"><i class="fas fa-history"></i> Donation History</a>
                        <a href="#"><i class="fas fa-chart-line"></i> Impact Report</a>
                        <a href="#"><i class="fas fa-cog"></i> Settings</a>
                    </div>
                    
                    <div class="impact-stats">
                        <h3>Your Impact</h3>
                        <?php
                        // quick stats from DB
                        $donationsCount = $conn->query("SELECT COUNT(*) as total FROM donations")->fetch_assoc()['total'];
                        $mealsCount = $conn->query("SELECT SUM(quantity) as total FROM donations")->fetch_assoc()['total'];
                        ?>
                        <div class="impact-stat">
                            <div class="stat-value"><?php echo $donationsCount ?: 0; ?></div>
                            <div class="stat-label">Donations</div>
                        </div>
                        <div class="impact-stat">
                            <div class="stat-value"><?php echo $mealsCount ?: 0; ?></div>
                            <div class="stat-label">Meals Provided</div>
                        </div>
                        <div class="impact-stat">
                            <div class="stat-value"><?php echo $mealsCount ? $mealsCount * 0.5 : 0; ?></div>
                            <div class="stat-label">Kg Saved</div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Dashboard -->
                <div class="dashboard-main">
                    <div class="dashboard-actions">
                        <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Post New Donation</a>
                    </div>
                    
                    <!-- Active Donations -->
                    <div class="dashboard-section">
                        <h3>Active Donations</h3>
                        <div class="food-listings">
                            <?php
                            $sql = "SELECT * FROM donations ORDER BY created_at DESC LIMIT 5";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $colors = ["#ff7675","#74b9ff","#55efc4","#ffeaa7","#a29bfe"];
                                while ($row = $result->fetch_assoc()) {
                                    $color = $colors[array_rand($colors)];
                                    $status_class = "status-available";
                                    if ($row['status'] == "Claimed") $status_class = "status-claimed";
                                    if ($row['status'] == "Completed") $status_class = "status-completed";

                                    echo "
                                    <div class='food-card'>
                                        <div class='food-image' style='background-color: $color;'>
                                            <i class='fas fa-utensils'></i>
                                        </div>
                                        <div class='food-details'>
                                            <h4 class='food-title'>{$row['food_item']}</h4>
                                            <div class='food-info'>
                                                <span><i class='fas fa-utensils'></i> {$row['quantity']}</span>
                                                <span><i class='fas fa-clock'></i> Pickup by {$row['pickup_time']}</span>
                                            </div>
                                            <p class='food-description'>{$row['description']}</p>
                                            <div class='food-status'>
                                                <span class='status-badge $status_class'>{$row['status']}</span>
                                                <button class='btn btn-outline'>Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                    ";
                                }
                            } else {
                                echo "<p class='text-muted'>No active donations yet. Post your first donation!</p>";
                            }
                            ?>
                        </div>
                    </div>
                    
                    <!-- Recent Donations -->
                    <div class="dashboard-section">
                        <h3>Recent Donations</h3>
                        <table class="donations-table">
                            <thead>
                                <tr>
                                    <th>Food Item</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql2 = "SELECT * FROM donations ORDER BY created_at DESC LIMIT 5";
                                $result2 = $conn->query($sql2);

                                if ($result2->num_rows > 0) {
                                    while ($row = $result2->fetch_assoc()) {
                                        $status_class = "status-available";
                                        if ($row['status'] == "Claimed") $status_class = "status-claimed";
                                        if ($row['status'] == "Completed") $status_class = "status-completed";

                                        echo "
                                        <tr>
                                            <td>{$row['food_item']}</td>
                                            <td>{$row['quantity']}</td>
                                            <td><span class='status-badge $status_class'>{$row['status']}</span></td>
                                            <td>".date("M d, Y", strtotime($row['created_at']))."</td>
                                        </tr>
                                        ";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-muted'>No recent donations found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/donor.js"></script>
</body>
</html>
