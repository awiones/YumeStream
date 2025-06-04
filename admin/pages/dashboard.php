<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth_check.php';

$pageTitle = 'Dashboard';

// Get total users count
$stmt = getDB()->query("SELECT COUNT(*) FROM users");
$totalUsers = $stmt->fetchColumn();

// Get total anime count
$stmt = getDB()->query("SELECT COUNT(*) FROM anime");
$totalAnime = $stmt->fetchColumn();

// Get total episodes count
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

// Get recent users
$stmt = getDB()->query("
    SELECT u.id, u.username, u.email, u.created_at, r.name as role
    FROM users u
    JOIN roles r ON u.role_id = r.id
    ORDER BY u.created_at DESC
    LIMIT 5
");
$recentUsers = $stmt->fetchAll();

include '../includes/header.php';
?>

<!-- Dashboard Stats -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Users</div>
            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($totalUsers); ?></div>
        <div class="stat-description">
            <span>All registered users</span>
            <span class="stat-trend trend-up">
                <i class="fas fa-arrow-up"></i> 12%
            </span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Anime</div>
            <div class="stat-icon anime">
                <i class="fas fa-film"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($totalAnime); ?></div>
        <div class="stat-description">
            <span>All anime series</span>
            <span class="stat-trend trend-up">
                <i class="fas fa-arrow-up"></i> 8%
            </span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Total Episodes</div>
            <div class="stat-icon views">
                <i class="fas fa-play-circle"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($totalEpisodes); ?></div>
        <div class="stat-description">
            <span>All episodes</span>
            <span class="stat-trend trend-up">
                <i class="fas fa-arrow-up"></i> 15%
            </span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">VIP Users</div>
            <div class="stat-icon revenue">
                <i class="fas fa-crown"></i>
            </div>
        </div>
        <div class="stat-value">
            <?php
            $vipCount = 0;
            foreach ($userRoles as $role) {
                if ($role['name'] === 'vip') {
                    $vipCount = $role['count'];
                    break;
                }
            }
            echo number_format($vipCount);
            ?>
        </div>
        <div class="stat-description">
            <span>Premium members</span>
            <span class="stat-trend trend-up">
                <i class="fas fa-arrow-up"></i> 5%
            </span>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Users</h3>
        <a href="/admin/pages/users.php" class="btn btn-sm btn-primary">
            <i class="fas fa-users"></i> View All
        </a>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentUsers as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="status <?php echo $user['role'] === 'admin' ? 'status-active' : ($user['role'] === 'vip' ? 'status-pending' : 'status-inactive'); ?>">
                                    <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td class="actions">
                                <a href="/admin/pages/users.php?edit=<?php echo $user['id']; ?>" class="btn-icon">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn-icon delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- User Roles Distribution -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Roles Distribution</h3>
    </div>
    <div class="card-body">
        <div id="userRolesChart" style="height: 300px;"></div>
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
                        '#10b981' // Member - Green
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