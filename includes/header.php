<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>YumeStream</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="/home">YumeStream</a>
                <span class="tagline">夢 • Dream</span>
            </div>
            <div class="nav-menu">
                <a href="/home" class="nav-link">Home</a>
                <a href="#" class="nav-link">Browse</a>
                <a href="#" class="nav-link">Schedule</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="profile-dropdown">
                        <div class="profile-avatar" id="profileAvatar">
                            <?php
                            // Get first letter of username
                            $firstLetter = isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'U';
                            echo $firstLetter;
                            ?>
                        </div>
                        <div class="dropdown-menu" id="profileDropdown">
                            <div class="dropdown-header">
                                <div class="dropdown-user-info">
                                    <span class="dropdown-username"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                                    <?php
                                    // Get user role
                                    $stmt = getDB()->prepare("
                                        SELECT r.name 
                                        FROM users u
                                        JOIN roles r ON u.role_id = r.id
                                        WHERE u.id = ?
                                    ");
                                    $stmt->execute([$_SESSION['user_id']]);
                                    $role = $stmt->fetchColumn();
                                    ?>
                                    <span class="dropdown-email"><?php echo ucfirst(htmlspecialchars($role)); ?></span>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="/profile" class="dropdown-item">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <a href="/settings" class="dropdown-item">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <?php if ($role === 'admin'): ?>
                                <a href="/admin" class="dropdown-item">
                                    <i class="fas fa-shield-alt"></i> Admin Panel
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="/logout" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login" class="nav-link">Login</a>
                    <a href="/register" class="btn-dream">
                        <svg class="btn-dream-shape" viewBox="0 0 140 50" preserveAspectRatio="none">
                            <path class="btn-dream-path" d="M 15,25 Q 15,8 30,8 L 100,8 Q 115,8 122,16 Q 130,25 122,34 Q 115,42 100,42 L 30,42 Q 15,42 15,25 Z" />
                            <path class="btn-dream-glow" d="M 15,25 Q 15,8 30,8 L 100,8 Q 115,8 122,16 Q 130,25 122,34 Q 115,42 100,42 L 30,42 Q 15,42 15,25 Z" />
                        </svg>
                        <span class="btn-dream-content">
                            <span class="btn-dream-text">Sign Up</span>
                        </span>
                        <span class="btn-dream-stars">
                            <i></i><i></i><i></i>
                        </span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="main-content">

        <script>
            // Profile dropdown functionality
            document.addEventListener('DOMContentLoaded', function() {
                const profileAvatar = document.getElementById('profileAvatar');
                const profileDropdown = document.getElementById('profileDropdown');

                if (profileAvatar && profileDropdown) {
                    // Toggle dropdown on avatar click
                    profileAvatar.addEventListener('click', function(e) {
                        e.stopPropagation();
                        profileDropdown.classList.toggle('show');
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(e) {
                        if (profileDropdown.classList.contains('show') && !profileDropdown.contains(e.target)) {
                            profileDropdown.classList.remove('show');
                        }
                    });
                }
            });
        </script>