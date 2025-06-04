<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/auth_check.php';

$pageTitle = 'User Management';

// Handle user actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // Change user role
    if ($action === 'change_role' && isset($_POST['role_id'])) {
        $roleId = (int)$_POST['role_id'];
        $stmt = getDB()->prepare("UPDATE users SET role_id = ? WHERE id = ?");
        if ($stmt->execute([$roleId, $userId])) {
            $_SESSION['success_message'] = "User role updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update user role.";
        }
        header('Location: /admin/pages/users.php');
        exit;
    }
    
    // Delete user
    if ($action === 'delete' && $userId > 0) {
        // Prevent deleting yourself
        if ($userId === $_SESSION['user_id']) {
            $_SESSION['error_message'] = "You cannot delete your own account.";
            header('Location: /admin/pages/users.php');
            exit;
        }
        
        $stmt = getDB()->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$userId])) {
            $_SESSION['success_message'] = "User deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete user.";
        }
        header('Location: /admin/pages/users.php');
        exit;
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Get total users count
$stmt = getDB()->query("SELECT COUNT(*) FROM users");
$totalUsers = $stmt->fetchColumn();
$totalPages = ceil($totalUsers / $perPage);

// Get users with pagination
$stmt = getDB()->prepare("
    SELECT u.id, u.username, u.email, u.created_at, r.id as role_id, r.name as role_name
    FROM users u
    JOIN roles r ON u.role_id = r.id
    ORDER BY u.created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// Get all roles for dropdown
$stmt = getDB()->query("SELECT id, name FROM roles ORDER BY id");
$roles = $stmt->fetchAll();

include '../includes/header.php';
?>

<!-- User Management -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Management</h3>
        <button class="btn btn-sm btn-primary" id="addUserBtn">
            <i class="fas fa-plus"></i> Add User
        </button>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="status <?php echo $user['role_name'] === 'admin' ? 'status-active' : ($user['role_name'] === 'vip' ? 'status-pending' : 'status-inactive'); ?>">
                                    <?php echo ucfirst(htmlspecialchars($user['role_name'])); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td class="actions">
                                <button class="btn-icon change-role" data-user-id="<?php echo $user['id']; ?>" data-username="<?php echo htmlspecialchars($user['username']); ?>" data-role-id="<?php echo $user['role_id']; ?>">
                                    <i class="fas fa-user-tag"></i>
                                </button>
                                <button class="btn-icon delete" data-user-id="<?php echo $user['id']; ?>" data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="pagination-item">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php else: ?>
                    <span class="pagination-item disabled">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                <?php endif; ?>
                
                <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $startPage + 4);
                if ($endPage - $startPage < 4) {
                    $startPage = max(1, $endPage - 4);
                }
                
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <a href="?page=<?php echo $i; ?>" class="pagination-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="pagination-item">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <span class="pagination-item disabled">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Change Role Modal (Hidden by default) -->
<div id="changeRoleModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); border-radius: 8px; width: 400px; max-width: 90%; padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <h3 style="margin-bottom: 1rem;">Change User Role</h3>
        <p style="margin-bottom: 1.5rem;">Change role for <span id="changeRoleUsername" style="font-weight: 600;"></span></p>
        
        <form action="" method="post" id="changeRoleForm">
            <div class="form-group">
                <label for="role_id" class="form-label">Select Role</label>
                <select name="role_id" id="role_id" class="form-select">
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role['id']; ?>"><?php echo ucfirst(htmlspecialchars($role['name'])); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" id="cancelChangeRole">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal (Hidden by default) -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); border-radius: 8px; width: 400px; max-width: 90%; padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <h3 style="margin-bottom: 1rem;">Delete User</h3>
        <p style="margin-bottom: 1.5rem;">Are you sure you want to delete <span id="deleteUsername" style="font-weight: 600;"></span>? This action cannot be undone.</p>
        
        <div class="form-actions">
            <a href="" id="confirmDelete" class="btn btn-danger">Delete User</a>
            <button type="button" class="btn btn-secondary" id="cancelDelete">Cancel</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Change Role Modal
        const changeRoleModal = document.getElementById('changeRoleModal');
        const changeRoleForm = document.getElementById('changeRoleForm');
        const changeRoleUsername = document.getElementById('changeRoleUsername');
        const roleSelect = document.getElementById('role_id');
        const cancelChangeRole = document.getElementById('cancelChangeRole');
        
        // Delete Modal
        const deleteModal = document.getElementById('deleteModal');
        const deleteUsername = document.getElementById('deleteUsername');
        const confirmDelete = document.getElementById('confirmDelete');
        const cancelDelete = document.getElementById('cancelDelete');
        
        // Change Role Button Click
        document.querySelectorAll('.change-role').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const username = this.getAttribute('data-username');
                const roleId = this.getAttribute('data-role-id');
                
                changeRoleUsername.textContent = username;
                roleSelect.value = roleId;
                changeRoleForm.action = `/admin/pages/users.php?action=change_role&id=${userId}`;
                
                changeRoleModal.style.display = 'flex';
            });
        });
        
        // Cancel Change Role
        cancelChangeRole.addEventListener('click', function() {
            changeRoleModal.style.display = 'none';
        });
        
        // Delete Button Click
        document.querySelectorAll('.delete').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const username = this.getAttribute('data-username');
                
                deleteUsername.textContent = username;
                confirmDelete.href = `/admin/pages/users.php?action=delete&id=${userId}`;
                
                deleteModal.style.display = 'flex';
            });
        });
        
        // Cancel Delete
        cancelDelete.addEventListener('click', function() {
            deleteModal.style.display = 'none';
        });
        
        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === changeRoleModal) {
                changeRoleModal.style.display = 'none';
            }
            if (event.target === deleteModal) {
                deleteModal.style.display = 'none';
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>