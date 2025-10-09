<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Note: You only need to include the database connection once.
include "php/db.php"; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - FoodLink</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/donor_dashboard.css">

</head>
<style>
/* 🌐 Global Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --primary: #007bff;
  --secondary: #6c757d;
  --success: #28a745;
  --danger: #dc3545;
  --warning: #ffc107;
  --bg: #f4f6f9;
  --white: #fff;
  --dark: #2d3436;
  --radius: 12px;
  --shadow: 0 4px 12px rgba(0,0,0,0.1);
}

body {
  font-family: "Poppins", sans-serif;
  background: var(--bg);
  color: var(--dark);
  line-height: 1.6;
}

/* 🔹 Header */
header {
  background: var(--white);
  padding: 1rem 2rem;
  box-shadow: var(--shadow);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-weight: 700;
  font-size: 1.5rem;
  color: var(--primary);
  display: flex;
  align-items: center;
  gap: 10px;
}

nav ul {
  list-style: none;
  display: flex;
  gap: 20px;
}

nav ul li a {
  text-decoration: none;
  color: var(--dark);
  font-weight: 500;
  transition: color 0.3s;
}

nav ul li a:hover {
  color: var(--primary);
}

/* Auth buttons */
.auth-buttons .btn-outline {
  border: 1px solid var(--primary);
  padding: 6px 14px;
  border-radius: var(--radius);
  color: var(--primary);
  transition: all 0.3s;
}

.auth-buttons .btn-outline:hover {
  background: var(--primary);
  color: var(--white);
}

/* 🔹 Dashboard Layout */
.dashboard {
  padding: 2rem 0;
}

.dashboard-content {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 2rem;
}

/* Sidebar */
.dashboard-sidebar {
  background: var(--white);
  padding: 1.5rem;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.sidebar-menu a {
  display: block;
  padding: 12px 15px;
  margin-bottom: 8px;
  border-radius: var(--radius);
  text-decoration: none;
  color: var(--dark);
  font-weight: 500;
  transition: background 0.3s;
}

.sidebar-menu a:hover {
  background: #f1f5ff;
  color: var(--primary);
}

.sidebar-menu a.active {
  background: var(--primary);
  color: var(--white);
}

/* Impact Stats */
.impact-stats h3 {
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.impact-stat {
  background: #f8f9fa;
  padding: 12px;
  border-radius: var(--radius);
  margin-bottom: 10px;
  text-align: center;
  transition: transform 0.3s;
}

.impact-stat:hover {
  transform: translateY(-4px);
}

.stat-value {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--primary);
}

.stat-label {
  font-size: 0.9rem;
  color: var(--secondary);
}

/* 🔹 Dashboard Main */
.dashboard-main {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.dashboard-actions {
  display: flex;
  justify-content: flex-end;
}

.btn {
  padding: 10px 18px;
  border-radius: var(--radius);
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s;
  cursor: pointer; /* Ensure buttons look clickable */
  border: none;
}

.btn-primary {
  background: var(--primary);
  color: var(--white);
}

.btn-primary:hover {
  background: #0056b3;
}

.btn-outline {
  border: 1px solid var(--secondary);
  color: var(--secondary);
  background: transparent;
}

.btn-outline:hover {
  background: var(--secondary);
  color: var(--white);
}

/* ✨ NEW - Delete Button Style */
.btn-danger {
    background-color: var(--danger);
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
}


/* 🔹 Active Donations */
.food-listings {
  display: grid;
  gap: 1.5rem;
}

.food-card {
  display: flex;
  background: var(--white);
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: transform 0.3s;
}

.food-card:hover {
  transform: translateY(-6px);
}

.food-image {
  flex: 0 0 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--white);
  font-size: 2rem;
}

.food-details {
  flex: 1;
  padding: 1rem;
}

.food-title {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.food-info {
  font-size: 0.9rem;
  color: var(--secondary);
  display: flex;
  gap: 15px;
  margin-bottom: 0.5rem;
}

.food-description {
  font-size: 0.95rem;
  margin-bottom: 1rem;
}

.food-status {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* ✨ NEW - Container for buttons */
.card-actions {
    display: flex;
    gap: 10px; /* Space between Edit and Delete buttons */
}


/* Status Badges */
.status-badge {
  padding: 4px 10px;
  border-radius: var(--radius);
  font-size: 0.85rem;
  font-weight: 500;
}

.status-available { background: var(--success); color: var(--white); }
.status-claimed { background: var(--warning); color: var(--dark); }
.status-completed { background: var(--secondary); color: var(--white); }

/* 🔹 Recent Donations Table */
.donations-table {
  width: 100%;
  border-collapse: collapse;
  background: var(--white);
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow);
}

.donations-table th,
.donations-table td {
  padding: 12px 16px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.donations-table th {
  background: #f1f5ff;
  font-weight: 600;
}

.donations-table tr:hover {
  background: #f9fbff;
}

.text-muted {
  text-align: center;
  color: var(--secondary);
}

/* 🔹 Responsive */
@media (max-width: 992px) {
  .dashboard-content {
    grid-template-columns: 1fr;
  }
}
</style>
<body>
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
                        <li><a href="php/impact_report.php">Impact</a></li>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <a href="#" class="btn btn-outline">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <section class="dashboard">
        <div class="container">
            <h2 class="section-title">Donor Dashboard</h2>
            
            <div class="dashboard-content">
                <div class="dashboard-sidebar">
                    <div class="sidebar-menu">
                        <a href="#" class="active"><i class="fas fa-home"></i> Overview</a>
                        <a href="php/post_donation.php"><i class="fas fa-plus-circle"></i> Post Donation</a>
                        <a href="php/donation_history.php"><i class="fas fa-history"></i> Donation History</a>
                        <a href="php/impact_report.php"><i class="fas fa-chart-line"></i> Impact Report</a>
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
                
                <div class="dashboard-main">
                    <div class="dashboard-actions">
                        <a href="php/post_donation.php" class="btn btn-primary">Post New Donation</a>
                    </div>
                    
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
                                                
                                                <div class='card-actions'>
                                                    <button class='btn btn-outline' action='php/edit_donation.php' >Edit</button>
                                                    
                                                    <form action='php/delete_post.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this donation post?');\">
                                                        <input type='hidden' name='donation_id' value='{$row['id']}'>
                                                        <button type='submit' name='delete_post' class='btn btn-danger'>Delete</button>
                                                    </form>
                                                </div>
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
                    
                    <div class="dashboard-section">
                        <h3>Recent Donations</h3>
                        <table class="donations-table">
                            <thead>
                                <tr>
                                    <th>Food Item</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th> </tr>
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
                                            
                                            <td>
                                                <form action='php/delete_post.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this donation post?');\">
                                                    <input type='hidden' name='donation_id' value='{$row['id']}'>
                                                    <button type='submit' name='delete_post' class='btn btn-danger'>Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        ";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-muted'>No recent donations found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>