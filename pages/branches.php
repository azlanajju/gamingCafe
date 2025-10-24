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
                <label class="form-label">Branch Manager *</label>
                <select class="form-control" id="branch-manager-select" required>
                    <option value="">Select a Manager</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Manager Name (Display) *</label>
                <input type="text" class="form-control" id="branch-manager" required readonly>
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
    // Load managers
    function loadManagers() {
        fetch(`${SITE_URL}/api/branches.php?action=managers`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const select = document.getElementById('branch-manager-select');
                    select.innerHTML = '<option value="">Select a Manager</option>';

                    result.data.forEach(manager => {
                        const option = document.createElement('option');
                        option.value = manager.id;
                        option.textContent = `${manager.full_name} (${manager.username})`;
                        select.appendChild(option);
                    });
                }
            });
    }

    // Load branches
    function loadBranchList() {
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
                        const managerDisplay = branch.manager_full_name || branch.manager_name || 'Not Assigned';
                        card.innerHTML = `
                        <h3>${branch.name}</h3>
                        <p><strong>Location:</strong> ${branch.location}</p>
                        <p><strong>Address:</strong> ${branch.address}</p>
                        <p><strong>Manager:</strong> ${managerDisplay}</p>
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

    // Manager selection handler
    document.getElementById('branch-manager-select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const managerNameField = document.getElementById('branch-manager');

        if (this.value) {
            managerNameField.value = selectedOption.textContent.split(' (')[0]; // Get just the name part
        } else {
            managerNameField.value = '';
        }
    });

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
            manager_id: document.getElementById('branch-manager-select').value || null,
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
                    loadBranchList();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    });

    // Edit branch
    function editBranch(id) {
        fetch(`${SITE_URL}/api/branches.php?action=get&id=${id}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const branch = result.data;
                    document.getElementById('branch-modal-title').textContent = 'Edit Branch';
                    document.getElementById('branch-id').value = branch.id;
                    document.getElementById('branch-name').value = branch.name;
                    document.getElementById('branch-location').value = branch.location;
                    document.getElementById('branch-address').value = branch.address;
                    document.getElementById('branch-contact').value = branch.contact;
                    document.getElementById('branch-manager').value = branch.manager_name;
                    document.getElementById('branch-manager-select').value = branch.manager_id || '';
                    document.getElementById('branch-timing').value = branch.timing;
                    document.getElementById('branch-console').value = branch.console_count;
                    document.getElementById('branch-established').value = branch.established_year;
                    document.getElementById('branch-active').checked = branch.status === 'Active';
                    document.getElementById('branch-modal').classList.remove('hidden');
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading branch data');
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
                        loadBranchList();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    }

    // Initial load
    loadManagers();
    loadBranches();

    // Listen for topbar branch changes and filter branches
    window.addEventListener('branchChanged', function(event) {
        console.log('Branches: Branch changed to:', event.detail);
        const selectedBranchId = event.detail.branchId;

        if (selectedBranchId) {
            // Filter branches by selected branch (show only that branch)
            loadBranchList();
            // Highlight the selected branch
            setTimeout(() => {
                const branchCards = document.querySelectorAll('.branch-card');
                branchCards.forEach(card => {
                    const branchId = card.dataset.branchId;
                    if (branchId === selectedBranchId) {
                        card.style.border = '2px solid var(--color-primary)';
                        card.style.backgroundColor = 'rgba(var(--color-primary-rgb), 0.1)';
                    } else {
                        card.style.border = '1px solid var(--color-border)';
                        card.style.backgroundColor = 'var(--color-surface)';
                    }
                });
            }, 500);
        } else {
            // Show all branches
            loadBranchList();
            // Reset all branch cards
            setTimeout(() => {
                const branchCards = document.querySelectorAll('.branch-card');
                branchCards.forEach(card => {
                    card.style.border = '1px solid var(--color-border)';
                    card.style.backgroundColor = 'var(--color-surface)';
                });
            }, 500);
        }
    });

    // Ensure branch selection is restored on this page
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for branches to load, then restore selection
        setTimeout(function() {
            if (typeof window.restoreBranchSelection === 'function') {
                console.log('Branches page: Attempting branch restoration...');
                window.restoreBranchSelection();
            }

            // Load branches and highlight selected one
            loadBranchList();
            const selectedBranchId = localStorage.getItem('selectedBranchId');
            if (selectedBranchId) {
                setTimeout(() => {
                    const branchCards = document.querySelectorAll('.branch-card');
                    branchCards.forEach(card => {
                        const branchId = card.dataset.branchId;
                        if (branchId === selectedBranchId) {
                            card.style.border = '2px solid var(--color-primary)';
                            card.style.backgroundColor = 'rgba(var(--color-primary-rgb), 0.1)';
                        }
                    });
                }, 500);
            }
        }, 1000);
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>