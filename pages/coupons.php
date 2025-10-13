<?php
$pageTitle = 'Coupon Management';
$currentPage = 'coupons';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="offers" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Coupon Management</h2>
        <button class="btn btn--primary" id="add-offer-btn">Create New Coupon</button>
    </div>
    <div class="offers-grid" id="offers-grid">
        <!-- Coupons will be loaded here -->
    </div>
</section>

<!-- Add Coupon Modal -->
<div id="offer-modal" class="modal hidden">
    <div class="modal-content">
        <h3 id="offer-modal-title">Create Coupon</h3>
        <form id="offer-form">
            <input type="hidden" id="offer-id">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input class="form-control" id="offer-name" type="text" required>
            </div>
            <div class="form-group">
                <label class="form-label">Code</label>
                <input class="form-control" id="offer-code" type="text" required>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control" id="offer-description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Discount Type</label>
                <select class="form-control" id="offer-discount-type" required>
                    <option value="">-- Select type --</option>
                    <option value="percentage">Percentage (%)</option>
                    <option value="flat">Flat (₹)</option>
                    <option value="time_bonus">Time Bonus (mins)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Discount Value</label>
                <input class="form-control" id="offer-discount-amount" type="number" step="0.01" min="0">
            </div>
            <div class="form-group">
                <label class="form-label">Usage Limit</label>
                <input class="form-control" id="offer-usage-limit" type="number" min="0" value="0">
            </div>
            <div class="form-group">
                <label class="form-label">Minimum Order Amount</label>
                <input class="form-control" id="offer-min-order-amount" type="number" step="0.01" min="0" value="0">
            </div>
            <div class="form-group">
                <label class="form-label">Valid From</label>
                <input class="form-control" id="offer-valid-from" type="date">
            </div>
            <div class="form-group">
                <label class="form-label">Valid To</label>
                <input class="form-control" id="offer-valid-to" type="date">
            </div>
            <div class="form-group">
                <label>
                    <input id="offer-active" type="checkbox" checked>
                    Active
                </label>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="cancel-offer">Cancel</button>
                <button type="submit" class="btn btn--primary">Save Coupon</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Load coupons
    function loadCoupons() {
        fetch(`${SITE_URL}/api/coupons.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const grid = document.getElementById('offers-grid');
                    grid.innerHTML = '';

                    result.data.forEach(coupon => {
                        const card = document.createElement('div');
                        card.className = 'coupon-card card';
                        const statusClass = coupon.status === 'Active' ? 'status-active' : 'status-inactive';
                        card.innerHTML = `
                        <h3>${coupon.name}</h3>
                        <p><strong>Code:</strong> ${coupon.code}</p>
                        <p><strong>Type:</strong> ${coupon.discount_type}</p>
                        <p><strong>Value:</strong> ${coupon.discount_value || 'N/A'}</p>
                        <p><strong>Used:</strong> ${coupon.times_used} / ${coupon.usage_limit || '∞'}</p>
                        <p class="${statusClass}"><strong>Status:</strong> ${coupon.status}</p>
                        <div class="card-actions">
                            <button class="btn btn--sm btn--primary" onclick="editCoupon(${coupon.id})">Edit</button>
                            <button class="btn btn--sm btn--danger" onclick="deleteCoupon(${coupon.id})">Delete</button>
                        </div>
                    `;
                        grid.appendChild(card);
                    });
                }
            });
    }

    // Add coupon button
    document.getElementById('add-offer-btn').addEventListener('click', () => {
        document.getElementById('offer-modal-title').textContent = 'Create New Coupon';
        document.getElementById('offer-form').reset();
        document.getElementById('offer-id').value = '';
        document.getElementById('offer-modal').classList.remove('hidden');
    });

    // Cancel
    document.getElementById('cancel-offer').addEventListener('click', () => {
        document.getElementById('offer-modal').classList.add('hidden');
    });

    // Form submit
    document.getElementById('offer-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const id = document.getElementById('offer-id').value;
        const data = {
            name: document.getElementById('offer-name').value,
            code: document.getElementById('offer-code').value,
            description: document.getElementById('offer-description').value,
            discount_type: document.getElementById('offer-discount-type').value,
            discount_value: document.getElementById('offer-discount-amount').value || null,
            base_minutes: null,
            bonus_minutes: null,
            loop_bonus: 0,
            usage_limit: document.getElementById('offer-usage-limit').value || 0,
            min_order_amount: document.getElementById('offer-min-order-amount').value || 0,
            valid_from: document.getElementById('offer-valid-from').value || null,
            valid_to: document.getElementById('offer-valid-to').value || null,
            branch_id: 1,
            status: document.getElementById('offer-active').checked ? 'Active' : 'Inactive'
        };

        const action = id ? 'update' : 'create';
        if (id) data.id = id;

        fetch(`${SITE_URL}/api/coupons.php?action=${action}`, {
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
                    document.getElementById('offer-modal').classList.add('hidden');
                    loadCoupons();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    });

    // Edit coupon
    function editCoupon(id) {
        fetch(`${SITE_URL}/api/coupons.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const coupon = result.data.find(c => c.id == id);
                    if (coupon) {
                        document.getElementById('offer-modal-title').textContent = 'Edit Coupon';
                        document.getElementById('offer-id').value = coupon.id;
                        document.getElementById('offer-name').value = coupon.name;
                        document.getElementById('offer-code').value = coupon.code;
                        document.getElementById('offer-description').value = coupon.description;
                        document.getElementById('offer-discount-type').value = coupon.discount_type;
                        document.getElementById('offer-discount-amount').value = coupon.discount_value;
                        document.getElementById('offer-usage-limit').value = coupon.usage_limit;
                        document.getElementById('offer-min-order-amount').value = coupon.min_order_amount;
                        document.getElementById('offer-valid-from').value = coupon.valid_from;
                        document.getElementById('offer-valid-to').value = coupon.valid_to;
                        document.getElementById('offer-active').checked = coupon.status === 'Active';
                        document.getElementById('offer-modal').classList.remove('hidden');
                    }
                }
            });
    }

    // Delete coupon
    function deleteCoupon(id) {
        if (confirm('Are you sure you want to delete this coupon?')) {
            fetch(`${SITE_URL}/api/coupons.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        loadCoupons();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    }

    // Initial load
    loadCoupons();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

