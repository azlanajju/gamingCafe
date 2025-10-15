<?php
$pageTitle = 'Profile Settings';
$currentPage = 'profile';
require_once __DIR__ . '/../includes/header.php';

$user = Auth::user();
?>

<section id="profile" class="content-section active">
    <h2 class="section-title">Profile Settings</h2>

    <div class="profile-grid">
        <!-- Personal Info -->
        <div class="card">
            <div class="card__body">
                <h3>Personal Information</h3>
                <form id="profile-form">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="profile-name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="profile-email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="profile-phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control readonly-field" value="<?php echo htmlspecialchars($user['role']); ?>" readonly>
                    </div>
                    <button type="submit" class="btn btn--primary">Update Profile</button>
                    <p id="profile-message" class="form-message"></p>
                </form>
            </div>
        </div>

        <!-- Password -->
        <div class="card">
            <div class="card__body">
                <h3>Change Password</h3>
                <form id="password-form">
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current-password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new-password" required>
                        <div id="password-strength" class="password-strength"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm-password" required>
                        <span id="password-match" class="password-strength"></span>
                    </div>
                    <button type="submit" class="btn btn--primary">Change Password</button>
                    <p id="password-message" class="form-message"></p>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    // Update profile
    document.getElementById('profile-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const data = {
            id: USER_ID,
            full_name: document.getElementById('profile-name').value,
            email: document.getElementById('profile-email').value,
            phone: document.getElementById('profile-phone').value,
            username: '<?php echo $user['username']; ?>',
            role: '<?php echo $user['role']; ?>',
            status: '<?php echo $user['status']; ?>',
            branch_id: <?php echo $user['branch_id']; ?>
        };

        fetch(`${SITE_URL}/api/users.php?action=update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                const msg = document.getElementById('profile-message');
                if (result.success) {
                    msg.textContent = result.message;
                    msg.style.color = 'green';
                } else {
                    msg.textContent = 'Error: ' + result.message;
                    msg.style.color = 'red';
                }
            });
    });

    // Change password
    document.getElementById('password-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const currentPwd = document.getElementById('current-password').value;
        const newPwd = document.getElementById('new-password').value;
        const confirmPwd = document.getElementById('confirm-password').value;

        if (newPwd !== confirmPwd) {
            document.getElementById('password-message').textContent = 'Passwords do not match';
            document.getElementById('password-message').style.color = 'red';
            return;
        }

        // In a real implementation, you would verify the current password
        // For now, we'll just update it
        const data = {
            id: USER_ID,
            full_name: '<?php echo $user['full_name']; ?>',
            username: '<?php echo $user['username']; ?>',
            email: '<?php echo $user['email']; ?>',
            phone: '<?php echo $user['phone'] ?? ''; ?>',
            role: '<?php echo $user['role']; ?>',
            status: '<?php echo $user['status']; ?>',
            branch_id: <?php echo $user['branch_id']; ?>,
            password: newPwd
        };

        fetch(`${SITE_URL}/api/users.php?action=update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                const msg = document.getElementById('password-message');
                if (result.success) {
                    msg.textContent = 'Password changed successfully';
                    msg.style.color = 'green';
                    document.getElementById('password-form').reset();
                } else {
                    msg.textContent = 'Error: ' + result.message;
                    msg.style.color = 'red';
                }
            });
    });

    // Password strength indicator
    document.getElementById('new-password').addEventListener('input', function() {
        const strength = document.getElementById('password-strength');
        const password = this.value;

        if (password.length < 6) {
            strength.textContent = 'Weak password';
            strength.style.color = 'red';
        } else if (password.length < 10) {
            strength.textContent = 'Medium strength';
            strength.style.color = 'orange';
        } else {
            strength.textContent = 'Strong password';
            strength.style.color = 'green';
        }
    });

    // Password match indicator
    document.getElementById('confirm-password').addEventListener('input', function() {
        const match = document.getElementById('password-match');
        const newPwd = document.getElementById('new-password').value;

        if (this.value === newPwd && this.value !== '') {
            match.textContent = '✓ Passwords match';
            match.style.color = 'green';
        } else if (this.value !== '') {
            match.textContent = '✗ Passwords do not match';
            match.style.color = 'red';
        } else {
            match.textContent = '';
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>






