<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YumeStream Admin Dashboard</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Top Navigation -->
        <nav class="admin-topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="admin-brand">
                    <h2>YumeStream Admin</h2>
                </div>
            </div>

            <div class="topbar-right">
                <div class="admin-notifications">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                </div>

                <div class="admin-profile-dropdown">
                    <button class="admin-profile-btn" id="adminProfileBtn">
                        <div class="admin-avatar">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                        <span class="admin-username"><?php echo htmlspecialchars($user['username']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <div class="admin-dropdown-menu" id="adminDropdownMenu">
                        <div class="dropdown-header">
                            <div class="dropdown-user-info">
                                <div class="dropdown-username"><?php echo htmlspecialchars($user['username']); ?></div>
                                <div class="dropdown-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                <div class="dropdown-role">Administrator</div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="?page=settings" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                        <a href="/home" class="dropdown-item">
                            <i class="fas fa-home"></i>
                            View Site
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="/public/?route=logout" class="dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>