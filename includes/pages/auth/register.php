<?php
$pageTitle = 'Register';

// Initialize session variables for registration process
if (!isset($_SESSION['registration'])) {
    $_SESSION['registration'] = [
        'step' => 1,
        'username' => '',
        'email' => ''
    ];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Username and Email validation
    if (isset($_POST['step']) && $_POST['step'] == '1') {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        $errors = [];
        
        // Validate username
        if (empty($username)) {
            $errors[] = "Username is required";
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = "Username must be between 3 and 50 characters";
        } else {
            // Check if username already exists
            $stmt = getDB()->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $errors[] = "Username already taken";
            }
        }
        
        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } else {
            // Check if email already exists
            $stmt = getDB()->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "Email already registered";
            }
        }
        
        // If no errors, proceed to step 2
        if (empty($errors)) {
            $_SESSION['registration']['username'] = $username;
            $_SESSION['registration']['email'] = $email;
            $_SESSION['registration']['step'] = 2;
        }
    }
    // Step 2: Password validation and account creation
    elseif (isset($_POST['step']) && $_POST['step'] == '2') {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        
        $errors = [];
        
        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        } elseif (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter";
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }
        
        // Confirm passwords match
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match";
        }
        
        // If no errors, create the account
        if (empty($errors)) {
            try {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert the new user
                $stmt = getDB()->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([
                    $_SESSION['registration']['username'],
                    $_SESSION['registration']['email'],
                    $hashedPassword
                ]);
                
                // Get the new user ID
                $userId = getDB()->lastInsertId();
                
                // Log the user in
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $_SESSION['registration']['username'];
                
                // Clear registration session data
                unset($_SESSION['registration']);
                
                // Redirect to home page
                header('Location: /home');
                exit;
            } catch (PDOException $e) {
                $errors[] = "Registration failed: " . $e->getMessage();
            }
        }
    }
}

// Determine current step
$currentStep = $_SESSION['registration']['step'] ?? 1;
?>

<link rel="stylesheet" href="/assets/css/auth.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2><i class="fas fa-play-circle" style="color: var(--accent); margin-right: 0.5rem;"></i>Create Account</h2>
            <p class="auth-subtitle">Join YumeStream and start your anime journey</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                <?php foreach ($errors as $error): ?>
                    <span><?php echo htmlspecialchars($error); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Step 1: Username and Email -->
        <?php if ($currentStep == 1): ?>
            <form method="POST" action="/register" class="auth-form">
                <input type="hidden" name="step" value="1">
                
                <div class="form-group">
                    <label for="username" class="form-label">
                        <i class="fas fa-user" style="margin-right: 0.5rem;"></i>Username
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-user input-icon"></i>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-input"
                            placeholder="Choose a username"
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ($_SESSION['registration']['username'] ?? ''); ?>"
                            required>
                    </div>
                </div>

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
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ($_SESSION['registration']['email'] ?? ''); ?>"
                            required>
                    </div>
                </div>

                <button type="submit" class="form-button">
                    <i class="fas fa-arrow-right"></i>
                    Continue
                </button>
            </form>
        <?php endif; ?>

        <!-- Step 2: Password -->
        <?php if ($currentStep == 2): ?>
            <form method="POST" action="/register" class="auth-form">
                <input type="hidden" name="step" value="2">
                
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
                            placeholder="Create a strong password"
                            required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon')">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>

                <div class="form-group password-group">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock" style="margin-right: 0.5rem;"></i>Confirm Password
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-lock input-icon"></i>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            class="form-input"
                            placeholder="Confirm your password"
                            required>
                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', 'toggleIconConfirm')">
                            <i class="fas fa-eye" id="toggleIconConfirm"></i>
                        </button>
                    </div>
                    <div class="password-match" id="passwordMatch"></div>
                </div>

                <div class="form-extras">
                    <label class="remember-me">
                        <input type="checkbox" name="terms" id="terms" required>
                        <span>I agree to the <a href="/terms" style="color: var(--accent);">Terms of Service</a> and <a href="/privacy" style="color: var(--accent);">Privacy Policy</a></span>
                    </label>
                </div>

                <button type="submit" class="form-button">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </button>
                
                <button type="button" class="social-button" style="margin-top: 10px; background-color: var(--bg-secondary);" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i>
                    Back to Previous Step
                </button>
            </form>
        <?php endif; ?>

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
                Already have an account? <a href="/login">Sign in</a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

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

    function goBack() {
        window.location.href = '/register?reset=1';
    }

    // Add loading state to form submission
    document.querySelector('.auth-form').addEventListener('submit', function(e) {
        const submitBtn = document.querySelector('.form-button');
        submitBtn.classList.add('loading');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
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

    // Password strength checker
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMatch = document.getElementById('passwordMatch');

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let message = '';

            if (password.length >= 8) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^A-Za-z0-9]/)) strength += 1;

            switch (strength) {
                case 0:
                case 1:
                    message = '<span style="color: var(--error);"><i class="fas fa-times-circle"></i> Weak password</span>';
                    break;
                case 2:
                case 3:
                    message = '<span style="color: var(--warning);"><i class="fas fa-exclamation-circle"></i> Medium password</span>';
                    break;
                case 4:
                case 5:
                    message = '<span style="color: var(--success);"><i class="fas fa-check-circle"></i> Strong password</span>';
                    break;
            }

            passwordStrength.innerHTML = message;
            
            // Check match if confirm password has value
            if (confirmPasswordInput.value) {
                checkPasswordMatch();
            }
        });
    }

    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }

    function checkPasswordMatch() {
        if (passwordInput.value === confirmPasswordInput.value) {
            passwordMatch.innerHTML = '<span style="color: var(--success);"><i class="fas fa-check-circle"></i> Passwords match</span>';
        } else {
            passwordMatch.innerHTML = '<span style="color: var(--error);"><i class="fas fa-times-circle"></i> Passwords do not match</span>';
        }
    }
</script>