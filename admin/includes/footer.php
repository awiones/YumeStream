            </div><!-- /.admin-main -->
        </main><!-- /.admin-content -->
    </div><!-- /.admin-container -->

    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const adminContainer = document.querySelector('.admin-container');
            
            if (sidebarToggle && adminContainer) {
                sidebarToggle.addEventListener('click', function() {
                    adminContainer.classList.toggle('sidebar-collapsed');
                });
            }
            
            // Check for saved sidebar state
            const sidebarState = localStorage.getItem('sidebarCollapsed');
            if (sidebarState === 'true') {
                adminContainer.classList.add('sidebar-collapsed');
            }
            
            // Save sidebar state when changed
            adminContainer.addEventListener('transitionend', function() {
                const isCollapsed = adminContainer.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });
        });
        
        // Dropdown functionality for notifications
        const notificationBtn = document.querySelector('.notification-btn');
        if (notificationBtn) {
            notificationBtn.addEventListener('click', function(e) {
                // Implement notification dropdown functionality here
                console.log('Notifications clicked');
            });
        }
        
        // Add active class to current page in sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-nav a');
            
            // Extract the page name from the current path
            let currentPage = '';
            if (currentPath === '/admin/' || currentPath === '/admin') {
                currentPage = 'dashboard';
            } else {
                // Extract page name from URL patterns like /admin/pagename or /admin/pages/pagename.php
                const adminMatch = currentPath.match(/^\/admin\/([a-zA-Z0-9_-]+)$/);
                const legacyMatch = currentPath.match(/^\/admin\/pages\/([a-zA-Z0-9_-]+)\.php$/);
                
                if (adminMatch) {
                    currentPage = adminMatch[1];
                } else if (legacyMatch) {
                    currentPage = legacyMatch[1];
                }
            }
            
            // Mark the appropriate link as active
            if (currentPage) {
                sidebarLinks.forEach(link => {
                    const href = link.getAttribute('href');
                    if (href === `/admin/${currentPage}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    </script>
</body>
</html>