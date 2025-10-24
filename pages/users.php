<?php
$pageTitle = 'User Management';
$currentPage = 'users';
require_once __DIR__ . '/../includes/header.php';

// Check admin access
if (!Auth::hasRole('Admin')) {
    die('Access denied. Admin privileges required.');
}
?>

<section id="user-management" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">User Management</h2>
        <button id="add-user-btn" class="btn btn--primary">Add New User</button>
    </div>
    <div class="card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="users-table-body">
                    <!-- Users will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- User Modal -->
<div id="user-modal" class="modal hidden">
    <div class="modal-content">
        <h3 id="user-modal-title">Add New User</h3>
        <form id="user-form">
            <input type="hidden" id="user-id">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" id="user-fullname" required>
            </div>
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="user-username" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" id="user-email" required>
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="tel" class="form-control" id="user-phone" required>
            </div>
            <?php if (Auth::hasRole('Admin')): ?>
                <div class="form-group">
                    <label class="form-label">Branch *</label>
                    <select class="form-control" id="user-branch" required>
                        <option value="">Select Branch</option>
                    </select>
                </div>
            <?php else: ?>
                <input type="hidden" id="user-branch" value="<?php echo Auth::userBranchId() ?? 1; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select class="form-control" id="user-role" required>
                    <option value="Staff">Staff</option>
                    <option value="Manager">Manager</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Password <span id="pwd-note">(leave empty to keep current)</span></label>
                <input type="password" class="form-control" id="user-password">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-control" id="user-status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="cancel-user">Cancel</button>
                <button type="submit" class="btn btn--primary">Save User</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Load branches
    function loadUserBranches() {
        fetch(`${SITE_URL}/api/users.php?action=branches`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const select = document.getElementById('user-branch');
                    if (select) {
                        select.innerHTML = '<option value="">Select Branch</option>';

                        result.data.forEach(branch => {
                            const option = document.createElement('option');
                            option.value = branch.id;
                            option.textContent = `${branch.name} - ${branch.location}`;
                            select.appendChild(option);
                        });
                    }
                }
            });
    }

    // Load users
    function loadUsers() {
        fetch(`${SITE_URL}/api/users.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const tbody = document.getElementById('users-table-body');
                    tbody.innerHTML = '';

                    result.data.forEach(user => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.full_name}</td>
                        <td>${user.phone}</td>
                        <td>${user.role}</td>
                        <td>${user.branch_name || 'Main Branch'}</td>
                        <td><span class="status-${user.status.toLowerCase()}">${user.status}</span></td>
                        <td>
                            <button class="btn btn--sm btn--primary" onclick="editUser(${user.id})">Edit</button>
                            <button class="btn btn--sm btn--danger" onclick="deleteUser(${user.id})">Delete</button>
                        </td>
                    `;
                        tbody.appendChild(tr);
                    });
                }
            });
    }

    // Add user button
    document.getElementById('add-user-btn').addEventListener('click', () => {
        document.getElementById('user-modal-title').textContent = 'Add New User';
        document.getElementById('user-form').reset();
        document.getElementById('user-id').value = '';
        document.getElementById('pwd-note').style.display = 'none';
        document.getElementById('user-password').required = true;
        document.getElementById('user-modal').classList.remove('hidden');
    });

    // Cancel
    document.getElementById('cancel-user').addEventListener('click', () => {
        document.getElementById('user-modal').classList.add('hidden');
    });

    // Form submit
    document.getElementById('user-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const id = document.getElementById('user-id').value;
        const data = {
            full_name: document.getElementById('user-fullname').value,
            username: document.getElementById('user-username').value,
            email: document.getElementById('user-email').value,
            phone: document.getElementById('user-phone').value,
            role: document.getElementById('user-role').value,
            status: document.getElementById('user-status').value,
            branch_id: document.getElementById('user-branch').value,
        };

        const password = document.getElementById('user-password').value;
        if (password) data.password = password;

        const action = id ? 'update' : 'create';
        if (id) data.id = id;

        fetch(`${SITE_URL}/api/users.php?action=${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    alert(result.message);
                    document.getElementById('user-modal').classList.add('hidden');
                    loadUsers();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    });

    // Edit user
    function editUser(id) {
        fetch(`${SITE_URL}/api/users.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const user = result.data.find(u => u.id == id);
                    if (user) {
                        document.getElementById('user-modal-title').textContent = 'Edit User';
                        document.getElementById('user-id').value = user.id;
                        document.getElementById('user-fullname').value = user.full_name;
                        document.getElementById('user-username').value = user.username;
                        document.getElementById('user-email').value = user.email;
                        document.getElementById('user-phone').value = user.phone;
                        document.getElementById('user-role').value = user.role;
                        const branchElement = document.getElementById('user-branch');
                        if (branchElement) {
                            branchElement.value = user.branch_id || '';
                        }
                        document.getElementById('user-status').value = user.status;
                        document.getElementById('user-password').value = '';
                        document.getElementById('pwd-note').style.display = 'inline';
                        document.getElementById('user-password').required = false;
                        document.getElementById('user-modal').classList.remove('hidden');
                    }
                }
            });
    }

    // Delete user
    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch(`${SITE_URL}/api/users.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        loadUsers();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    }

    // Initial load
    loadUserBranches();
    loadUsers();

    // Listen for topbar branch changes and filter users
    window.addEventListener('branchChanged', function(event) {
        console.log('Users: Branch changed to:', event.detail);
        const selectedBranchId = event.detail.branchId;

        if (selectedBranchId) {
            // Filter users by selected branch
            loadUsers(selectedBranchId);
        } else {
            // Show all users
            loadUsers();
        }
    });

    // Ensure branch selection is restored on this page
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for branches to load, then restore selection
        setTimeout(function() {
            if (typeof window.restoreBranchSelection === 'function') {
                console.log('Users page: Attempting branch restoration...');
                window.restoreBranchSelection();
            }

            // Load users based on current topbar selection
            const selectedBranchId = localStorage.getItem('selectedBranchId');
            if (selectedBranchId) {
                loadUsers(selectedBranchId);
            } else {
                loadUsers();
            }
        }, 1000);
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>