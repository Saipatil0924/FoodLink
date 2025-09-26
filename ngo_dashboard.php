<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
include "php/db.php";
?>

<?php
include "php/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NGO Dashboard - FoodLink</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="css/ngo_dashboard.css">

  <link rel="stylesheet" href="css/style.css">

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
            <li><a href="#" class="active">Dashboard</a></li>
            <li><a href="#">Pickups</a></li>
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
      <h2 class="section-title">NGO Dashboard</h2>
      
      <div class="dashboard-content">
        <!-- Sidebar -->
        <div class="dashboard-sidebar">
          <div class="sidebar-menu">
            <a href="#" class="active"><i class="fas fa-home"></i> Overview</a>
            <a href="#"><i class="fas fa-search"></i> Find Donations</a>
            <a href="#"><i class="fas fa-truck"></i> Scheduled Pickups</a>
            <a href="#"><i class="fas fa-history"></i> Pickup History</a>
            <a href="#"><i class="fas fa-chart-line"></i> Impact Report</a>
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
          </div>
          
          <div class="impact-stats">
            <h3>Your Impact</h3>
            <div class="impact-stat">
              <div class="stat-value">28</div>
              <div class="stat-label">Pickups</div>
            </div>
            <div class="impact-stat">
              <div class="stat-value">450</div>
              <div class="stat-label">Meals Provided</div>
            </div>
            <div class="impact-stat">
              <div class="stat-value">12</div>
              <div class="stat-label">Partner Donors</div>
            </div>
          </div>
        </div>

        <!-- Main Section -->
        <div class="dashboard-main">
          <div class="dashboard-section">
            <h3>
              Available Donations Near You
              <div class="search-container">
                <input type="text" id="donation-search" placeholder="Search donations...">
                <button><i class="fas fa-search"></i></button>
              </div>
            </h3>
            <div class="food-listings" id="donation-list">
              <?php
              $result = $conn->query("SELECT * FROM donations WHERE status='Available' ORDER BY created_at DESC");
              while($row = $result->fetch_assoc()) {
                  echo "
                  <div class='food-card'>
                    <div class='food-image' style='background-color:#74b9ff;'>
                      <i class='fas fa-apple-alt'></i>
                    </div>
                    <div class='food-details'>
                      <h4 class='food-title'>{$row['food_item']}</h4>
                      <div class='food-info'>
                        <span><i class='fas fa-utensils'></i> {$row['quantity']}</span>
                        <span><i class='fas fa-clock'></i> Pickup by {$row['pickup_time']}</span>
                      </div>
                      <p class='food-description'>{$row['description']}</p>
                      <div class='food-status'>
                        <span class='status-badge status-available'>{$row['status']}</span>
                        <button class='btn btn-primary schedule-btn' data-id='{$row['id']}'>Schedule Pickup</button>
                      </div>
                    </div>
                  </div>";
              }
              ?>
            </div>
          </div>

          <div class="dashboard-section">
            <h3>Scheduled Pickups</h3>
            <div class="pickups-list" id="pickup-list">
              <!-- Later load pickups dynamically -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal for Scheduling Pickup -->
  <div class="modal" id="schedule-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Schedule Pickup</h3>
        <button class="close-modal">&times;</button>
      </div>
      <form id="schedule-form">
        <input type="hidden" id="donation-id">
        <div class="form-group">
          <label for="pickup-date">Pickup Date</label>
          <input type="date" id="pickup-date" required>
        </div>
        <div class="form-group">
          <label for="pickup-time">Pickup Time</label>
          <input type="time" id="pickup-time" required>
        </div>
        <div class="form-group">
          <label for="notes">Additional Notes (Optional)</label>
          <textarea id="notes" rows="3"></textarea>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline close-modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Schedule Pickup</button>
        </div>
      </form>
    </div>
  </div>

  <script src="js/ngo.js"></script>
</body>
</html>
