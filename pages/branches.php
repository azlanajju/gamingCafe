<?php
$pageTitle = 'Branch Management';
$currentPage = 'branches';
require_once __DIR__ . '/../includes/header.php';

// Check admin access
if (!Auth::hasRole('Admin')) {
    die('Access denied. Admin privileges required.');
}
?>

<section id="branch-management" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Branch Management</h2>
        <button id="add-branch-btn" class="btn btn--primary">Add New Branch</button>
    </div>
    <div class="branches-grid" id="branches-grid">
        <!-- Branches will be loaded here -->
    </div>
</section>

<!-- Branch Modal -->
<div id="branch-modal" class="modal hidden">
    <div class="modal-content">
        <h3 id="branch-modal-title">Add New Branch</h3>
        <form id="branch-form">
            <input type="hidden" id="branch-id">
            <div class="form-group">
                <label class="form-label">Branch Name *</label>
                <input type="text" class="form-control" id="branch-name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Location *</label>
                <input type="text" class="form-control" id="branch-location" required>
            </div>
            <div class="form-group">
                <label class="form-label">Address *</label>
                <input type="text" class="form-control" id="branch-address" required>
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number *</label>
                <input type="tel" class="form-control" id="branch-contact" required>
            </div>
            <div class="form-group">
                <label class="form-label">Manager Name *</label>
                <input type="text" class="form-control" id="branch-manager" required>
            </div>
            <div class="form-group">
                <label class="form-label">Timing *</label>
                <input type="text" class="form-control" id="branch-timing" placeholder="e.g. 10:00 AM - 12:00 AM" required>
            </div>
            <div class="form-group">
                <label class="form-label">Number of Consoles</label>
                <input type="number" class="form-control" id="branch-console" min="1" max="100" value="10">
            </div>
            <div class="form-group">
                <label class="form-label">Established Year</label>
                <input type="number" class="form-control" id="branch-established" min="2000" max="2100" value="2023">
            </div>
            <div class="form-group">
                <label>
                    <input id="branch-active" type="checkbox" checked>
                    Active
                </label>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="cancel-branch">Cancel</button>
                <button type="submit" class="btn btn--primary">Save Branch</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Load branches
    function loadBranches() {
        fetch(`${SITE_URL}/api/branches.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const grid = document.getElementById('branches-grid');
                    grid.innerHTML = '';

                    result.data.forEach(branch => {
                        const card = document.createElement('div');
                        card.className = 'branch-card card';
                        const statusClass = branch.status === 'Active' ? 'status-active' : 'status-inactive';
                        card.innerHTML = `
                        <h3>${branch.name}</h3>
                        <p><strong>Location:</strong> ${branch.location}</p>
                        <p><strong>Address:</strong> ${branch.address}</p>
                        <p><strong>Manager:</strong> ${branch.manager_name}</p>
                        <p><strong>Contact:</strong> ${branch.contact}</p>
                        <p><strong>Consoles:</strong> ${branch.console_count}</p>
                        <p class="${statusClass}"><strong>Status:</strong> ${branch.status}</p>
                        <div class="card-actions">
                            <button class="btn btn--sm btn--primary" onclick="editBranch(${branch.id})">Edit</button>
                            ${branch.id !== 1 ? `<button class="btn btn--sm btn--danger" onclick="deleteBranch(${branch.id})">Delete</button>` : ''}
                        </div>
                    `;
                        grid.appendChild(card);
                    });
                }
            });
    }

    // Add branch button
    document.getElementById('add-branch-btn').addEventListener('click', () => {
        document.getElementById('branch-modal-title').textContent = 'Add New Branch';
        document.getElementById('branch-form').reset();
        document.getElementById('branch-id').value = '';
        document.getElementById('branch-modal').classList.remove('hidden');
    });

    // Cancel
    document.getElementById('cancel-branch').addEventListener('click', () => {
        document.getElementById('branch-modal').classList.add('hidden');
    });

    // Form submit
    document.getElementById('branch-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const id = document.getElementById('branch-id').value;
        const data = {
            name: document.getElementById('branch-name').value,
            location: document.getElementById('branch-location').value,
            address: document.getElementById('branch-address').value,
            contact: document.getElementById('branch-contact').value,
            manager_name: document.getElementById('branch-manager').value,
            timing: document.getElementById('branch-timing').value,
            console_count: document.getElementById('branch-console').value,
            established_year: document.getElementById('branch-established').value,
            status: document.getElementById('branch-active').checked ? 'Active' : 'Inactive'
        };

        const action = id ? 'update' : 'create';
        if (id) data.id = id;

        fetch(`${SITE_URL}/api/branches.php?action=${action}`, {
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
                    document.getElementById('branch-modal').classList.add('hidden');
                    loadBranches();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    });

    // Edit branch
    function editBranch(id) {
        fetch(`${SITE_URL}/api/branches.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const branch = result.data.find(b => b.id == id);
                    if (branch) {
                        document.getElementById('branch-modal-title').textContent = 'Edit Branch';
                        document.getElementById('branch-id').value = branch.id;
                        document.getElementById('branch-name').value = branch.name;
                        document.getElementById('branch-location').value = branch.location;
                        document.getElementById('branch-address').value = branch.address;
                        document.getElementById('branch-contact').value = branch.contact;
                        document.getElementById('branch-manager').value = branch.manager_name;
                        document.getElementById('branch-timing').value = branch.timing;
                        document.getElementById('branch-console').value = branch.console_count;
                        document.getElementById('branch-established').value = branch.established_year;
                        document.getElementById('branch-active').checked = branch.status === 'Active';
                        document.getElementById('branch-modal').classList.remove('hidden');
                    }
                }
            });
    }

    // Delete branch
    function deleteBranch(id) {
        if (confirm('Are you sure you want to delete this branch?')) {
            fetch(`${SITE_URL}/api/branches.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        loadBranches();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    }

    // Initial load
    loadBranches();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

