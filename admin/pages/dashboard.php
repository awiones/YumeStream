<?php
// Get statistics from database
try {
    // Get total users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $totalUsers = $stmt->fetch()['total'];

    // Get total anime
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM anime");
    $totalAnime = $stmt->fetch()['total'];

    // Get total episodes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM episodes");
    $totalEpisodes = $stmt->fetch()['total'];

    // Get total watch history entries (as views)
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM watch_history");
    $totalViews = $stmt->fetch()['total'];

    // Get recent users
    $stmt = $pdo->prepare("
        SELECT u.*, r.name as role_name 
        FROM users u 
        JOIN roles r ON u.role_id = r.id 
        ORDER BY u.created_at DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $recentUsers = $stmt->fetchAll();

    // Get recent anime
    $stmt = $pdo->query("SELECT * FROM anime ORDER BY created_at DESC LIMIT 5");
    $recentAnime = $stmt->fetchAll();
} catch (PDOException $e) {
    $totalUsers = $totalAnime = $totalEpisodes = $totalViews = 0;
    $recentUsers = $recentAnime = [];
}
?>

<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back, <?php echo htmlspecialchars($user['username']); ?>! Here's what's happening with YumeStream.</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($totalUsers); ?></div>
        <div class="stat-label">Total Users</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span style="color: #10b981;">+12% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon anime">
                <i class="fas fa-film"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($totalAnime); ?></div>
        <div class="stat-label">Total Anime</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span style="color: #10b981;">+5% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon episodes">
                <i class="fas fa-play-circle"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($totalEpisodes); ?></div>
        <div class="stat-label">Total Episodes</div>
        <div class="stat-change negative">
            <i class="fas fa-arrow-down"></i>
            <span style="color: #ef4444;">-3% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon views">
                <i class="fas fa-eye"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($totalViews); ?></div>
        <div class="stat-label">Total Views</div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span style="color: #10b981;">+15% from last month</span>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <!-- Recent Users -->
    <div class="admin-card">
        <div class="card-header">
            <h3 class="card-title">Recent Users</h3>
            <a href="/admin/page/users" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentUsers)): ?>
                <p style="color: #ffffff; text-align: center; padding: 2rem;">No users found.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentUsers as $recentUser): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($recentUser['username']); ?></td>
                                <td><?php echo htmlspecialchars($recentUser['email']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $recentUser['role_name'] === 'admin' ? 'danger' : ($recentUser['role_name'] === 'vip' ? 'warning' : 'info'); ?>">
                                        <?php echo htmlspecialchars($recentUser['role_name']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($recentUser['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Anime -->
    <div class="admin-card">
        <div class="card-header">
            <h3 class="card-title">Recent Anime</h3>
            <a href="/admin/page/anime" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentAnime)): ?>
                <p style="color: #ffffff; text-align: center; padding: 2rem;">No anime found.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Episodes</th>
                            <th>Status</th>
                            <th>Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentAnime as $anime): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($anime['title']); ?></td>
                                <td><?php echo $anime['episodes']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $anime['status'] === 'completed' ? 'success' : ($anime['status'] === 'ongoing' ? 'warning' : 'info'); ?>">
                                        <?php echo htmlspecialchars($anime['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($anime['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="admin-card">
    <div class="card-header">
        <h3 class="card-title">Quick Actions</h3>
    </div>
    <div class="card-body">
        <div class="page-actions">
            <a href="/admin/page/anime/add" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add New Anime
            </a>
            <a href="/admin/page/episodes/add" class="btn btn-success">
                <i class="fas fa-play-circle"></i>
                Add New Episode
            </a>
            <a href="/admin/page/users" class="btn btn-secondary">
                <i class="fas fa-users"></i>
                Manage Users
            </a>
            <a href="/admin/page/reports" class="btn btn-secondary">
                <i class="fas fa-chart-bar"></i>
                View Reports
            </a>
        </div>
    </div>
</div>