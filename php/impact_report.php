<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// CORRECTED PATH: Since this file is in the 'php' folder, 
// it can find 'db.php' in the same folder directly.
include "db.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impact Report - FoodLink</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/donor_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
/* Reusing most styles from donor_dashboard.css */

.impact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.impact-card {
    background: var(--white);
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    text-align: center;
    transition: all 0.3s ease;
}

.impact-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.12);
}

.impact-card .icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: var(--primary);
}

.impact-card .value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark);
}

.impact-card .label {
    font-size: 1rem;
    color: var(--secondary);
}

.chart-container {
    background: var(--white);
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.section-title {
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
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
                        <li><a href="../index.html">Home</a></li>
                        <li><a href="../donor_dashboard.php">Dashboard</a></li>
                        <li><a href="#">Donations</a></li>
                        <li><a href="impact_report.php">Impact</a></li>
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
            <div class="dashboard-content">
                <div class="dashboard-sidebar">
                    <div class="sidebar-menu">
                        <a href="../donor_dashboard.php"><i class="fas fa-home"></i> Overview</a>
                        <a href="post_donation.php"><i class="fas fa-plus-circle"></i> Post Donation</a>
                        <a href="#"><i class="fas fa-history"></i> Donation History</a>
                        <a href="impact_report.php" class="active"><i class="fas fa-chart-line"></i> Impact Report</a>
                        <a href="#"><i class="fas fa-cog"></i> Settings</a>
                    </div>
                </div>
                
                <div class="dashboard-main">
                    <h2 class="section-title">Your Impact Report 📈</h2>

                    <?php
                        // --- 1. Fetch Key Metrics ---
                        $totalDonationsResult = $conn->query("SELECT COUNT(*) as total FROM donations");
                        $totalDonations = $totalDonationsResult->fetch_assoc()['total'] ?: 0;

                        $totalMealsResult = $conn->query("SELECT SUM(quantity) as total FROM donations");
                        $totalMeals = $totalMealsResult->fetch_assoc()['total'] ?: 0;

                        $foodSavedKg = $totalMeals * 0.5; 
                        $co2SavedKg = $foodSavedKg * 2.5;

                        // --- 2. Fetch Data for Chart ---
                        $chartQuery = "
                            SELECT 
                                DATE_FORMAT(created_at, '%b %Y') as month, 
                                COUNT(id) as donation_count 
                            FROM donations 
                            GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                            ORDER BY DATE_FORMAT(created_at, '%Y-%m') ASC
                        ";
                        $chartResult = $conn->query($chartQuery);
                        
                        $chartLabels = [];
                        $chartData = [];
                        if ($chartResult->num_rows > 0) {
                            while($row = $chartResult->fetch_assoc()) {
                                $chartLabels[] = $row['month'];
                                $chartData[] = $row['donation_count'];
                            }
                        }
                    ?>
                    
                    <div class="impact-grid">
                        <div class="impact-card">
                            <div class="icon" style="color: #007bff;"><i class="fas fa-hands-helping"></i></div>
                            <div class="value"><?php echo $totalDonations; ?></div>
                            <div class="label">Total Donations Made</div>
                        </div>
                        <div class="impact-card">
                            <div class="icon" style="color: #28a745;"><i class="fas fa-utensils"></i></div>
                            <div class="value"><?php echo $totalMeals; ?></div>
                            <div class="label">Meals Provided</div>
                        </div>
                        <div class="impact-card">
                            <div class="icon" style="color: #ffc107;"><i class="fas fa-weight-hanging"></i></div>
                            <div class="value"><?php echo number_format($foodSavedKg, 1); ?> kg</div>
                            <div class="label">Food Saved from Waste</div>
                        </div>
                        <div class="impact-card">
                            <div class="icon" style="color: #17a2b8;"><i class="fas fa-leaf"></i></div>
                            <div class="value"><?php echo number_format($co2SavedKg, 1); ?> kg</div>
                            <div class="label">CO₂ Emissions Saved</div>
                        </div>
                    </div>
                    
                    <div class="dashboard-section">
                        <h3>Donations Over Time</h3>
                        <div class="chart-container">
                            <canvas id="donationsChart"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('donationsChart').getContext('2d');
        const chartLabels = <?php echo json_encode($chartLabels); ?>;
        const chartData = <?php echo json_encode($chartData); ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: '# of Donations',
                    data: chartData,
                    backgroundColor: 'rgba(0, 123, 255, 0.6)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#343a40',
                        titleFont: { size: 16 },
                        bodyFont: { size: 14 },
                        padding: 12,
                        cornerRadius: 6
                    }
                }
            }
        });
    });
    </script>

</body>
</html>