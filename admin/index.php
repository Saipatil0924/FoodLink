
<?php
include "../php/db.php"; // adjust path if needed

// Get Stats
$donations = $conn->query("SELECT COUNT(*) AS total FROM donations")->fetch_assoc()['total'];
$ngos = $conn->query("SELECT COUNT(*) AS total FROM users WHERE type='ngo'")->fetch_assoc()['total'];
$volunteers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE type='volunteer'")->fetch_assoc()['total'] ?? 0;
$food_saved = $conn->query("SELECT SUM(quantity) AS total FROM donations WHERE status='completed'")->fetch_assoc()['total'] ?? 0;

// Recent donations
$recent = $conn->query("
    SELECT d.*, donor.name AS donor_name, ngo.name AS ngo_name
    FROM donations d
    LEFT JOIN users donor ON d.donor_id = donor.id
    LEFT JOIN users ngo ON d.ngo_id = ngo.id
    ORDER BY d.id DESC
    LIMIT 5
");

?>

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodLink Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="d-flex" id="wrapper">
  <nav class="bg-dark text-white p-3 vh-100" id="sidebar">
    <h3 class="text-center mb-4">🍱 FoodLink</h3>
    <ul class="nav flex-column">
      <li class="nav-item"><a href="index.php" class="nav-link text-white"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li class="nav-item"><a href="donors.php" class="nav-link text-white"><i class="bi bi-person-heart"></i> Donors</a></li>
      <li class="nav-item"><a href="ngos.php" class="nav-link text-white"><i class="bi bi-people"></i> NGOs</a></li>
      <li class="nav-item"><a href="volunteers.php" class="nav-link text-white"><i class="bi bi-person-badge"></i> Volunteers</a></li>
      <li class="nav-item"><a href="donations.php" class="nav-link text-white"><i class="bi bi-box-seam"></i> Donations</a></li>
      <li class="nav-item"><a href="reports.php" class="nav-link text-white"><i class="bi bi-bar-chart"></i> Reports</a></li>
      <li class="nav-item"><a href="feedback.php" class="nav-link text-white"><i class="bi bi-chat-dots"></i> Feedback</a></li>
      <li class="nav-item"><a href="settings.php" class="nav-link text-white"><i class="bi bi-gear"></i> Settings</a></li>
      <li class="nav-item mt-3"><a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
  </nav>

  <div class="container-fluid p-4" id="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Admin Dashboard</h2>
      <div class="d-flex align-items-center">
        <span class="me-2">Welcome, Super Admin</span>
        <img src="assets/img/admin-avatar.png" alt="Admin" width="40" class="rounded-circle border">
      </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title text-muted">Total Donations</h5>
            <h2 class="text-success"><?= $donations ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title text-muted">Active NGOs</h5>
            <h2 class="text-primary"><?= $ngos ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title text-muted">Volunteers</h5>
            <h2 class="text-info"><?= $volunteers ?></h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title text-muted">Food Saved (kg)</h5>
            <h2 class="text-warning"><?= $food_saved ?></h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Donations Table -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white">
        <h5 class="mb-0">Recent Donations</h5>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Donor</th>
              <th>Food Type</th>
              <th>Quantity</th>
              <th>NGO Assigned</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $recent->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['donor_name'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['food_type']) ?></td>
              <td><?= htmlspecialchars($row['quantity']) ?></td>
              <td><?= htmlspecialchars($row['ngo_name'] ?? 'Pending') ?></td>
              <td><span class="badge bg-<?= $row['status']=='Delivered'?'success':'warning' ?>"><?= $row['status'] ?></span></td>
              <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Chart -->
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0">Donation Statistics</h5>
      </div>
      <div class="card-body">
        <canvas id="donationChart" height="100"></canvas>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('donationChart');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    datasets: [{
      label: 'Donations per Day',
      data: [12, 19, 7, 15, 10, 5, 8],
      backgroundColor: 'rgba(13, 110, 253, 0.6)',
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
</body>
</html>
