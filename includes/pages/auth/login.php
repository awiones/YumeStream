<?php
$pageTitle = 'Login';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $errors = [];

    // Validate input
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {
        // Check user credentials
        $stmt = getDB()->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Get user role
            $stmt = getDB()->prepare("
                SELECT r.name 
                FROM users u
                JOIN roles r ON u.role_id = r.id
                WHERE u.id = ?
            ");
            $stmt->execute([$user['id']]);
            $role = $stmt->fetchColumn();
            
            // Set role in session
            $_SESSION['user_role'] = $role;
            
            // Check if there's a redirect URL in session
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header('Location: ' . $redirect);
                exit;
            }
            
            // Default redirect based on role
            if ($role === 'admin') {
                header('Location: /admin');
            } else {
                header('Location: /home');
            }
            exit;
        } else {
            $errors[] = "Invalid email or password";
        }
    }
}

// Check if there's a registration success message
$registrationSuccess = false;
if (isset($_GET['registered']) && $_GET['registered'] == 'true') {
    $registrationSuccess = true;
}
?>

<link rel="stylesheet" href="/assets/css/auth.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2><i class="fas fa-play-circle" style="color: var(--accent); margin-right: 0.5rem;"></i>Welcome Back</h2>
            <p class="auth-subtitle">Continue your anime journey with YumeStream</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                <?php foreach ($errors as $error): ?>
                    <span><?php echo htmlspecialchars($error); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($registrationSuccess): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                <span>Account created successfully! You can now log in.</span>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" class="auth-form">
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>Email Address
                </label>
                <div style="position: relative;">
                    <i class="fas fa-envelope input-icon"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="Enter your email address"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        required>
                </div>
            </div>

            <div class="form-group password-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock" style="margin-right: 0.5rem;"></i>Password
                </label>
                <div style="position: relative;">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Enter your password"
                        required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-extras">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember me</span>
                </label>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="form-button">
                <i class="fas fa-sign-in-alt"></i>
                Sign In to YumeStream
            </button>
        </form>

        <div class="auth-divider">
            <span>or continue with</span>
        </div>

        <div class="social-login">
            <button type="button" class="social-button">
                <i class="fab fa-google"></i>
                Continue with Google
            </button>
        </div>

        <div class="form-links">
            <div class="form-link">
                New to YumeStream? <a href="/register">Create an account</a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Add loading state to form submission
    document.querySelector('.auth-form').addEventListener('submit', function(e) {
        const submitBtn = document.querySelector('.form-button');
        submitBtn.classList.add('loading');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
    });

    // Add focus effects
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.parentElement.classList.remove('focused');
        });
    });
</script>