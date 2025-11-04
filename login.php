<?php
require_once 'config/config.php';
require_once 'config/auth.php';

// Redirect if already logged in
if (Auth::check()) {
    header('Location: pages/dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember-me']);

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $result = Auth::login($username, $password);

        if ($result['success']) {
            // Set remember me cookie if checked
            if ($rememberMe) {
                setcookie('remember_user', $username, time() + (86400 * 30), "/");
            }

            header('Location: pages/dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

// Get remembered username
$rememberedUser = $_COOKIE['remember_user'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Login Screen -->
    <div id="login-screen" class="login-container">
        <div class="login-background">
            <div class="login-background-overlay"></div>
        </div>
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <img src="assets/logo.png" alt="GameBot Logo" class="logo">
                </div>
                <h1 class="login-title"><?php echo SITE_NAME; ?></h1>
                <p class="login-subtitle">Management System</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error" style="margin-bottom: 1rem; padding: 0.75rem; background: #fee; border: 1px solid #fcc; border-radius: 4px; color: #c33;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success" style="margin-bottom: 1rem; padding: 0.75rem; background: #efe; border: 1px solid #cfc; border-radius: 4px; color: #3c3;">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <input type="text" name="username" class="login-input" placeholder="Username or Email" value="<?php echo htmlspecialchars($rememberedUser); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <circle cx="12" cy="16" r="1"></circle>
                                <path d="m7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" class="login-input" placeholder="Password" required>
                        <button type="button" class="password-toggle" id="password-toggle">
                            <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-off-icon hidden" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember-me" id="remember-me" <?php echo $rememberedUser ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                        <span class="remember-text">Remember me</span>
                    </label>
                </div>
                <button type="submit" class="login-button">
                    <span>Login</span>
                    <svg class="arrow-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12,5 19,12 12,19"></polyline>
                    </svg>
                </button>
            </form>

            <div style="margin-top: 1rem; text-align: center; color: #888; font-size: 0.875rem;">
                <!-- <p>Default credentials: <strong>admin</strong> / <strong>admin123</strong></p> -->
            </div>
        </div>
    </div>

    <script>
        // Password toggle
        document.getElementById('password-toggle')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = this.querySelector('.eye-icon');
            const eyeOffIcon = this.querySelector('.eye-off-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        });
    </script>
</body>

</html>

