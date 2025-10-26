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
                    <?php if (Auth::hasRole('Admin')): ?>
                        <div class="topbar-branch-selector">
                            <label for="topbar-branch-selector" class="branch-label">Branch:</label>
                            <select id="topbar-branch-selector" class="branch-dropdown">
                                <option value="">All Branches</option>
                            </select>
                        </div>
                    <?php endif; ?>
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
                <?php if (Auth::hasRole('Admin') || Auth::hasRole('Manager')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/pages/transactions.php" class="nav-item <?php echo ($currentPage ?? '') === 'transactions' ? 'active' : ''; ?>">💳 Transactions</a></li>
                <?php endif; ?>
                <li><a href="<?php echo SITE_URL; ?>/pages/games.php" class="nav-item <?php echo ($currentPage ?? '') === 'games' ? 'active' : ''; ?>">🎯 Game Management</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/fandd-management.php" class="nav-item <?php echo ($currentPage ?? '') === 'fandd-management' ? 'active' : ''; ?>">🍕 F&D Management</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/coupons.php" class="nav-item <?php echo ($currentPage ?? '') === 'coupons' ? 'active' : ''; ?>">🎟️ Coupon Management</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/pricing.php" class="nav-item <?php echo ($currentPage ?? '') === 'pricing' ? 'active' : ''; ?>">💰 Price Management</a></li>
                <?php if (Auth::hasRole('Admin') || Auth::hasRole('Manager')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/pages/users.php" class="nav-item <?php echo ($currentPage ?? '') === 'users' ? 'active' : ''; ?>">👨‍💼 User Management</a></li>
                <?php endif; ?>
                <?php if (Auth::hasRole('Admin')): ?>
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

                // Global branch selection storage
                let selectedBranchId = localStorage.getItem('selectedBranchId') || '';
                let selectedBranchName = localStorage.getItem('selectedBranchName') || 'All Branches';

                function logout() {
                    if (confirm('Are you sure you want to logout?')) {
                        window.location.href = SITE_URL + '/logout.php';
                    }
                }

                // Load branches for dropdown (Admin only)
                async function loadBranches() {
                    if (USER_ROLE !== 'Admin') {
                        console.log('Not an Admin, skipping branch loading');
                        return;
                    }

                    try {
                        console.log('Loading branches for Admin...');
                        const response = await fetch(`${SITE_URL}/api/branches.php?action=list`);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();
                        console.log('Branches API response:', result);

                        if (result.success && result.data && Array.isArray(result.data)) {
                            const branchSelector = document.getElementById('topbar-branch-selector');
                            if (branchSelector) {
                                // Clear existing options except "All Branches"
                                branchSelector.innerHTML = '<option value="">All Branches</option>';

                                result.data.forEach(branch => {
                                    const option = document.createElement('option');
                                    option.value = branch.id;
                                    option.textContent = branch.name;
                                    branchSelector.appendChild(option);
                                });

                                console.log(`Loaded ${result.data.length} branches into topbar dropdown`);

                                // Set selected value from localStorage AFTER populating options
                                if (selectedBranchId) {
                                    branchSelector.value = selectedBranchId;
                                    console.log('Topbar branch selection restored:', selectedBranchId, selectedBranchName);
                                }

                                // Add change event listener
                                branchSelector.addEventListener('change', function() {
                                    selectedBranchId = this.value;
                                    selectedBranchName = this.options[this.selectedIndex].textContent;

                                    // Store in localStorage
                                    localStorage.setItem('selectedBranchId', selectedBranchId);
                                    localStorage.setItem('selectedBranchName', selectedBranchName);

                                    console.log('Topbar branch changed:', selectedBranchId, selectedBranchName);

                                    // Trigger custom event for other pages to listen
                                    window.dispatchEvent(new CustomEvent('branchChanged', {
                                        detail: {
                                            branchId: selectedBranchId,
                                            branchName: selectedBranchName
                                        }
                                    }));
                                });
                            } else {
                                console.error('Topbar branch selector element not found');
                            }
                        } else {
                            console.error('Invalid branches data:', result);
                        }
                    } catch (error) {
                        console.error('Error loading branches:', error);
                        // Add fallback option if API fails
                        const branchSelector = document.getElementById('topbar-branch-selector');
                        if (branchSelector) {
                            branchSelector.innerHTML = '<option value="">All Branches</option>';
                            console.log('Added fallback "All Branches" option to topbar');
                        }
                    }
                }

                // Function to restore branch selection (can be called from any page)
                function restoreBranchSelection() {
                    if (USER_ROLE !== 'Admin') return;

                    const branchSelector = document.getElementById('topbar-branch-selector');
                    if (branchSelector) {
                        // Check if options are loaded
                        if (branchSelector.options.length > 1) {
                            if (selectedBranchId) {
                                branchSelector.value = selectedBranchId;
                                console.log('Topbar branch selection restored:', selectedBranchId, selectedBranchName);
                            } else {
                                branchSelector.value = '';
                                console.log('Topbar branch selection reset to All Branches');
                            }
                            return true; // Success
                        } else {
                            console.log('Topbar branch options not loaded yet, retrying...');
                            return false; // Not ready
                        }
                    } else {
                        console.log('Topbar branch selector not found');
                    }
                    return false;
                }

                // Make function globally available
                window.restoreBranchSelection = restoreBranchSelection;

                // Track restoration attempts to prevent multiple simultaneous attempts
                let restorationInProgress = false;
                let restorationAttempts = 0;
                const maxRestorationAttempts = 5;

                // Aggressive restoration with retries
                function attemptRestoration() {
                    if (restorationInProgress) {
                        console.log('Restoration already in progress, skipping...');
                        return;
                    }

                    restorationInProgress = true;
                    restorationAttempts = 0;

                    const tryRestore = () => {
                        restorationAttempts++;
                        console.log(`Restoration attempt ${restorationAttempts}/${maxRestorationAttempts}`);

                        if (restoreBranchSelection()) {
                            console.log('Branch selection restored successfully');
                            restorationInProgress = false;
                            return;
                        }

                        if (restorationAttempts < maxRestorationAttempts) {
                            console.log(`Retrying in 300ms...`);
                            setTimeout(tryRestore, 300);
                        } else {
                            console.log('Failed to restore branch selection after maximum attempts');
                            restorationInProgress = false;
                        }
                    };

                    tryRestore();
                }

                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function() {
                    loadBranches();
                    // Start aggressive restoration
                    setTimeout(attemptRestoration, 100);
                });

                // Also restore when window loads (fallback)
                window.addEventListener('load', function() {
                    setTimeout(attemptRestoration, 200);
                });

                // Additional restoration on page visibility change (for SPA-like behavior)
                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden) {
                        setTimeout(restoreBranchSelection, 100);
                    }
                });
            </script>