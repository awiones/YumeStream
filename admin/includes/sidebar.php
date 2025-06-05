        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-content">
                <div class="sidebar-menu">
                    <div class="menu-section">
                        <div class="menu-title">Main</div>
                        <ul class="menu-list">
                            <li class="menu-item <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                                <a href="?page=dashboard" class="menu-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="menu-section">
                        <div class="menu-title">Content Management</div>
                        <ul class="menu-list">
                            <li class="menu-item <?php echo $page === 'anime' ? 'active' : ''; ?>">
                                <a href="?page=anime" class="menu-link">
                                    <i class="fas fa-film"></i>
                                    <span>Anime</span>
                                </a>
                            </li>
                            <li class="menu-item <?php echo $page === 'episodes' ? 'active' : ''; ?>">
                                <a href="?page=episodes" class="menu-link">
                                    <i class="fas fa-play-circle"></i>
                                    <span>Episodes</span>
                                </a>
                            </li>
                            <li class="menu-item <?php echo $page === 'genres' ? 'active' : ''; ?>">
                                <a href="?page=genres" class="menu-link">
                                    <i class="fas fa-tags"></i>
                                    <span>Genres</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="menu-section">
                        <div class="menu-title">User Management</div>
                        <ul class="menu-list">
                            <li class="menu-item <?php echo $page === 'users' ? 'active' : ''; ?>">
                                <a href="?page=users" class="menu-link">
                                    <i class="fas fa-users"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="menu-section">
                        <div class="menu-title">Analytics</div>
                        <ul class="menu-list">
                            <li class="menu-item <?php echo $page === 'reports' ? 'active' : ''; ?>">
                                <a href="?page=reports" class="menu-link">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Reports</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="menu-section">
                        <div class="menu-title">System</div>
                        <ul class="menu-list">
                            <li class="menu-item <?php echo $page === 'settings' ? 'active' : ''; ?>">
                                <a href="?page=settings" class="menu-link">
                                    <i class="fas fa-cog"></i>
                                    <span>Settings</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="sidebar-footer">
                    <div class="sidebar-user">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?php echo htmlspecialchars($user['username']); ?></div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main" id="adminMain">