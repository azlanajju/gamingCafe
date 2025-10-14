<?php
require_once __DIR__ . '/../config/auth.php';
Auth::require();
$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Dashboard'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div id="main-app" class="main-app">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <h1 class="header-title"><?php echo SITE_NAME; ?></h1>
                <div class="header-actions">
                    <button id="theme-toggle" class="btn btn--secondary btn--sm">🌙</button>
                    <div class="user-info">
                        <span id="current-user"><?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                        <button id="logout-btn" class="btn btn--outline btn--sm" onclick="logout()">Logout</button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="sidebar">
            <div class="sidebar-logo">
                <img src="<?php echo SITE_URL; ?>/assets/logo.png" alt="GAMEBOT Logo" class="logo-img">
            </div>
            <ul class="nav-menu">
                <li><a href="<?php echo SITE_URL; ?>/pages/dashboard.php" class="nav-item <?php echo ($currentPage ?? '') === 'dashboard' ? 'active' : ''; ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/console-mapping.php" class="nav-item <?php echo ($currentPage ?? '') === 'console-mapping' ? 'active' : ''; ?>">🎮 Console Mapping</a></li>
                <?php if (Auth::hasRole('Admin')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/pages/transactions.php" class="nav-item <?php echo ($currentPage ?? '') === 'transactions' ? 'active' : ''; ?>">💳 Transactions</a></li>
                <?php endif; ?>
                <li><a href="<?php echo SITE_URL; ?>/pages/games.php" class="nav-item <?php echo ($currentPage ?? '') === 'games' ? 'active' : ''; ?>">🎯 Game Management</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/fandd-management.php" class="nav-item <?php echo ($currentPage ?? '') === 'fandd-management' ? 'active' : ''; ?>">🍕 F&D Management</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/coupons.php" class="nav-item <?php echo ($currentPage ?? '') === 'coupons' ? 'active' : ''; ?>">🎟️ Coupon Management</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/pricing.php" class="nav-item <?php echo ($currentPage ?? '') === 'pricing' ? 'active' : ''; ?>">💰 Price Management</a></li>
                <?php if (Auth::hasRole('Admin')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/pages/users.php" class="nav-item admin-only <?php echo ($currentPage ?? '') === 'users' ? 'active' : ''; ?>">👨‍💼 User Management</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/branches.php" class="nav-item admin-only <?php echo ($currentPage ?? '') === 'branches' ? 'active' : ''; ?>">🏢 Branch Management</a></li>
                <?php endif; ?>
                <li><a href="<?php echo SITE_URL; ?>/pages/profile.php" class="nav-item <?php echo ($currentPage ?? '') === 'profile' ? 'active' : ''; ?>">👤 Profile Settings</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <script>
                const SITE_URL = '<?php echo SITE_URL; ?>';
                const USER_ROLE = '<?php echo $currentUser['role']; ?>';
                const USER_ID = <?php echo $currentUser['id']; ?>;

                function logout() {
                    if (confirm('Are you sure you want to logout?')) {
                        window.location.href = SITE_URL + '/logout.php';
                    }
                }
            </script>