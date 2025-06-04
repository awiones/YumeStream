<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth_check.php';

$pageTitle = 'Reports';

// Get some basic statistics for reports
$stmt = getDB()->query("SELECT COUNT(*) FROM users");
$totalUsers = $stmt->fetchColumn();

$stmt = getDB()->query("SELECT COUNT(*) FROM anime");
$totalAnime = $stmt->fetchColumn();

$stmt = getDB()->query("SELECT COUNT(*) FROM episodes");
$totalEpisodes = $stmt->fetchColumn();

// Get user roles distribution
$stmt = getDB()->query("
    SELECT r.name, COUNT(u.id) as count
    FROM users u
    JOIN roles r ON u.role_id = r.id
    GROUP BY r.name
");
$userRoles = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Statistics</h3>
    </div>
    <div class="card-body">
        <div id="userRolesChart" style="height: 300px;"></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Content Statistics</h3>
    </div>
    <div class="card-body">
        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Users</div>
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($totalUsers); ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Anime</div>
                    <div class="stat-icon anime">
                        <i class="fas fa-film"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($totalAnime); ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Episodes</div>
                    <div class="stat-icon views">
                        <i class="fas fa-play-circle"></i>
                    </div>
                </div>
                <div class="stat-value"><?php echo number_format($totalEpisodes); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for visualization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User roles distribution chart
        const userRolesData = <?php echo json_encode($userRoles); ?>;
        const labels = userRolesData.map(item => item.name.charAt(0).toUpperCase() + item.name.slice(1));
        const data = userRolesData.map(item => item.count);
        
        const ctx = document.getElementById('userRolesChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#3b82f6', // Admin - Blue
                        '#f59e0b', // VIP - Orange
                        '#10b981'  // Member - Green
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#cbd5e1',
                            font: {
                                family: 'Inter',
                                size: 12
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        bodyFont: {
                            family: 'Inter'
                        },
                        titleFont: {
                            family: 'Inter',
                            weight: 'bold'
                        },
                        displayColors: false,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>