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
    <title>Advanced Impact Report - FoodLink</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/donor_dashboard.css"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
/* ==============================================
   ADVANCED & MODERN CSS FOR DASHBOARD
   ============================================== */

/* --- 1. Theming & Custom Properties --- */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

:root {
    --font-family: 'Inter', sans-serif;
    
    /* A refined, professional color palette */
    --primary-color: #4F46E5; /* Indigo */
    --secondary-color: #10B981; /* Emerald */
    --background-color: #F3F4F6; /* Cool Gray 100 */
    --surface-color: rgba(255, 255, 255, 0.6);
    --text-color: #1F2937; /* Cool Gray 800 */
    --text-muted-color: #6B7280; /* Cool Gray 500 */
    --border-color: rgba(255, 255, 255, 0.4);
    
    /* Sizing & Effects */
    --radius: 16px;
    --shadow-light: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    --shadow-strong: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
    --transition-smooth: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* --- 2. Base & Global Styles --- */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    background-color: var(--background-color);
    color: var(--text-color);
    font-family: var(--font-family);
    line-height: 1.6;
}

.dashboard-main {
    padding: 2rem;
    animation: fadeInPage 0.5s ease-out forwards;
}

@keyframes fadeInPage {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.section-title {
    font-size: 2.25rem;
    margin-bottom: 2rem;
    font-weight: 700;
    color: var(--text-color);
    position: relative;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #E5E7EB; /* Cool Gray 200 */
}

/* --- 3. Impact Card Styling --- */
.impact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

@keyframes popIn {
    0% {
        opacity: 0;
        transform: scale(0.95) translateY(10px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.impact-card {
    background: var(--surface-color);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    padding: 2rem;
    box-shadow: var(--shadow-light);
    transition: all var(--transition-smooth);
    opacity: 0;
    transform: scale(0.95);
    animation: popIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.impact-card:nth-child(1) { animation-delay: 0.1s; }
.impact-card:nth-child(2) { animation-delay: 0.2s; }
.impact-card:nth-child(3) { animation-delay: 0.3s; }
.impact-card:nth-child(4) { animation-delay: 0.4s; }

.impact-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-strong);
}

.impact-card .icon {
    width: 64px;
    height: 64px;
    margin-bottom: 1rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.icon-donations { background: linear-gradient(135deg, #4F46E5, #7C3AED); } /* Indigo -> Purple */
.icon-meals { background: linear-gradient(135deg, #059669, #10B981); } /* Emerald */
.icon-food-saved { background: linear-gradient(135deg, #D97706, #FBBF24); } /* Amber -> Yellow */
.icon-co2-saved { background: linear-gradient(135deg, #0891B2, #22D3EE); } /* Cyan */

.impact-card .value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-color);
    line-height: 1.1;
    margin-bottom: 0.25rem;
}

.impact-card .label {
    font-size: 1rem;
    color: var(--text-muted-color);
    font-weight: 400;
}

/* --- 4. Chart Container Styling --- */
.dashboard-section {
    background: #FFFFFF;
    border-radius: var(--radius);
    box-shadow: var(--shadow-strong);
    padding: 2rem;
    animation: popIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    animation-delay: 0.5s;
    opacity: 0;
}

.dashboard-section h3 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
    color: var(--text-color);
}

.chart-container {
    height: 400px;
    position: relative;
}

#donationsChart {
    width: 100% !important;
    height: 100% !important;
}

/* --- 5. Responsive Adjustments --- */
@media (max-width: 992px) {
    .dashboard-content {
        flex-direction: column;
    }
    .dashboard-sidebar {
        width: 100%;
        margin-bottom: 2rem;
    }
}
@media (max-width: 768px) {
    .dashboard-main { padding: 1rem; }
    .section-title { font-size: 1.75rem; }
    .impact-card .value { font-size: 2rem; }
}
</style>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo"><i class="fas fa-utensils"></i><span>FoodLink</span></div>
                <nav>
                    <ul>
                        <li><a href="../index.html">Home</a></li>
                        <li><a href="../donor_dashboard.php">Dashboard</a></li>
                        <li><a href="#">Donations</a></li>
                        <li><a href="impact_report.php">Impact</a></li>
                    </ul>
                </nav>
                <div class="auth-buttons"><a href="#" class="btn btn-outline">Logout</a></div>
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
                
                <main class="dashboard-main">
                    <h2 class="section-title">Your Collective Impact 🌍</h2>

                    <?php
                        // PHP data fetching logic remains the same
                        $totalDonationsResult = $conn->query("SELECT COUNT(*) as total FROM donations");
                        $totalDonations = $totalDonationsResult->fetch_assoc()['total'] ?: 0;
                        $totalMealsResult = $conn->query("SELECT SUM(quantity) as total FROM donations");
                        $totalMeals = $totalMealsResult->fetch_assoc()['total'] ?: 0;
                        $foodSavedKg = $totalMeals * 0.5; 
                        $co2SavedKg = $foodSavedKg * 2.5;

                        $chartQuery = "
                            SELECT DATE_FORMAT(created_at, '%b %Y') as month, COUNT(id) as donation_count 
                            FROM donations 
                            GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                            ORDER BY DATE_FORMAT(created_at, '%Y-%m') ASC";
                        $chartResult = $conn->query($chartQuery);
                        
                        $chartLabels = [];
                        $chartData = [];
                        if ($chartResult && $chartResult->num_rows > 0) {
                            while($row = $chartResult->fetch_assoc()) {
                                $chartLabels[] = $row['month'];
                                $chartData[] = $row['donation_count'];
                            }
                        }
                    ?>
                    
                    <div class="impact-grid">
                        <div class="impact-card">
                            <div class="icon icon-donations"><i class="fas fa-hands-helping"></i></div>
                            <div class="value"><?php echo $totalDonations; ?></div>
                            <div class="label">Total Donations</div>
                        </div>
                        <div class="impact-card">
                            <div class="icon icon-meals"><i class="fas fa-utensils"></i></div>
                            <div class="value"><?php echo $totalMeals; ?></div>
                            <div class="label">Meals Provided</div>
                        </div>
                        <div class="impact-card">
                            <div class="icon icon-food-saved"><i class="fas fa-weight-hanging"></i></div>
                            <div class="value"><?php echo number_format($foodSavedKg, 1); ?> kg</div>
                            <div class="label">Food Saved from Waste</div>
                        </div>
                        <div class="impact-card">
                            <div class="icon icon-co2-saved"><i class="fas fa-leaf"></i></div>
                            <div class="value"><?php echo number_format($co2SavedKg, 1); ?> kg</div>
                            <div class="label">CO₂ Emissions Reduced</div>
                        </div>
                    </div>
                    
                    <section class="dashboard-section">
                        <h3>Donations Trend</h3>
                        <div class="chart-container">
                            <canvas id="donationsChart"></canvas>
                        </div>
                    </section>

                </main>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('donationsChart')?.getContext('2d');
        if (!ctx) return;

        const chartLabels = <?php echo json_encode($chartLabels); ?>;
        const chartData = <?php echo json_encode($chartData); ?>;

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.7)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(ctx, {
            type: 'line', // Changed to a line chart for a better trend visualization
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Number of Donations',
                    data: chartData,
                    backgroundColor: gradient,
                    borderColor: '#4F46E5', // var(--primary-color)
                    borderWidth: 3,
                    pointBackgroundColor: '#4F46E5',
                    pointBorderColor: '#FFFFFF',
                    pointHoverRadius: 8,
                    pointRadius: 5,
                    fill: true,
                    tension: 0.4 // Makes the line curved
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#6B7280',
                            font: { family: "'Inter', sans-serif" },
                            stepSize: 1
                        },
                        grid: {
                            color: '#E5E7EB',
                            drawBorder: false,
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6B7280',
                            font: { family: "'Inter', sans-serif" }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        bodyFont: { size: 12, family: "'Inter', sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                interaction: {
                  intersect: false,
                  mode: 'index',
                },
            }
        });
    });
    </script>
</body>
</html>