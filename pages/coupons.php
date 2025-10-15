<?php
$pageTitle = 'Coupon Management';
$currentPage = 'coupons';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="offers" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Coupon Management</h2>
        <?php if (Auth::hasRole('Admin') || Auth::hasRole('Manager')): ?>
            <button class="btn btn--primary" id="add-offer-btn">Create New Coupon</button>
        <?php endif; ?>
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
            <div class="form-group" id="discount-value-group">
                <label class="form-label">Discount Value</label>
                <input class="form-control" id="offer-discount-amount" type="number" step="0.01" min="0">
            </div>

            <!-- Time Bonus specific fields -->
            <div class="form-group" id="time-bonus-fields" style="display: none;">
                <label class="form-label">Base Minutes (Play at least)</label>
                <input class="form-control" id="offer-base-minutes" type="number" min="0" placeholder="e.g., 60">
            </div>

            <div class="form-group" id="bonus-minutes-group" style="display: none;">
                <label class="form-label">Bonus Minutes (Free per cycle)</label>
                <input class="form-control" id="offer-bonus-minutes" type="number" min="0" placeholder="e.g., 30">
            </div>

            <div class="form-group" id="loop-bonus-group" style="display: none;">
                <label>
                    <input id="offer-loop-bonus" type="checkbox">
                    Loop Bonus (apply bonus every cycle)
                </label>
                <small style="display: block; color: #666; font-size: 12px; margin-top: 5px;">If checked: 2 hours = 1 hour free. If unchecked: 2 hours = only 30 mins free</small>
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
                        let valueDisplay = '';
                        if (coupon.discount_type === 'time_bonus') {
                            valueDisplay = `<p><strong>Base:</strong> ${coupon.base_minutes || 0} mins</p>
                                           <p><strong>Bonus:</strong> ${coupon.bonus_minutes || 0} mins</p>
                                           <p><strong>Loop:</strong> ${coupon.loop_bonus ? 'Yes' : 'No'}</p>`;
                        } else {
                            valueDisplay = `<p><strong>Value:</strong> ${coupon.discount_value || 'N/A'}</p>`;
                        }

                        // Get discount type icon and color
                        let typeIcon = '🎟️';
                        let typeColor = '#6B7280';
                        let typeBg = '#F3F4F6';

                        if (coupon.discount_type === 'percentage') {
                            typeIcon = '📊';
                            typeColor = '#3B82F6';
                            typeBg = '#EFF6FF';
                        } else if (coupon.discount_type === 'flat') {
                            typeIcon = '💰';
                            typeColor = '#10B981';
                            typeBg = '#ECFDF5';
                        } else if (coupon.discount_type === 'time_bonus') {
                            typeIcon = '⏰';
                            typeColor = '#F59E0B';
                            typeBg = '#FFFBEB';
                        }

                        card.innerHTML = `
                        <div class="coupon-header">
                            <div class="coupon-type-badge" style="background: ${typeBg}; color: ${typeColor};">
                                <span class="type-icon">${typeIcon}</span>
                                <span class="type-text">${coupon.discount_type.replace('_', ' ').toUpperCase()}</span>
                            </div>
                            <div class="coupon-status ${statusClass}">
                                <span class="status-dot"></span>
                                ${coupon.status}
                            </div>
                        </div>
                        
                        <div class="coupon-content">
                            <h3 class="coupon-name">${coupon.name}</h3>
                            <div class="coupon-code">
                                <span class="code-label">Code:</span>
                                <span class="code-value">${coupon.code}</span>
                            </div>
                            
                            <div class="coupon-details">
                                ${valueDisplay}
                            </div>
                            
                            <div class="coupon-usage">
                                <div class="usage-bar">
                                    <div class="usage-fill" style="width: ${Math.min((coupon.times_used / (coupon.usage_limit || 1)) * 100, 100)}%"></div>
                                </div>
                                <span class="usage-text">${coupon.times_used} / ${coupon.usage_limit || '∞'} used</span>
                            </div>
                        </div>
                        
                        ${USER_ROLE === 'Admin' || USER_ROLE === 'Manager' ? `
                        <div class="coupon-actions">
                            <button class="btn btn--sm btn--primary" onclick="editCoupon(${coupon.id})">
                                <span class="btn-icon">✏️</span>
                                Edit
                            </button>
                            <button class="btn btn--sm btn--danger" onclick="deleteCoupon(${coupon.id})">
                                <span class="btn-icon">🗑️</span>
                                Delete
                            </button>
                        </div>
                        ` : ''}
                    `;
                        grid.appendChild(card);
                    });
                }
            });
    }

    // Show/hide fields based on discount type
    function toggleDiscountFields() {
        const discountType = document.getElementById('offer-discount-type').value;
        const discountValueGroup = document.getElementById('discount-value-group');
        const timeBonusFields = document.getElementById('time-bonus-fields');
        const bonusMinutesGroup = document.getElementById('bonus-minutes-group');
        const loopBonusGroup = document.getElementById('loop-bonus-group');

        if (discountType === 'time_bonus') {
            discountValueGroup.style.display = 'none';
            timeBonusFields.style.display = 'block';
            bonusMinutesGroup.style.display = 'block';
            loopBonusGroup.style.display = 'block';
        } else {
            discountValueGroup.style.display = 'block';
            timeBonusFields.style.display = 'none';
            bonusMinutesGroup.style.display = 'none';
            loopBonusGroup.style.display = 'none';
        }
    }

    // Add coupon button
    const addOfferBtn = document.getElementById('add-offer-btn');
    if (addOfferBtn) {
        addOfferBtn.addEventListener('click', () => {
            document.getElementById('offer-modal-title').textContent = 'Create New Coupon';
            document.getElementById('offer-form').reset();
            document.getElementById('offer-id').value = '';
            toggleDiscountFields(); // Reset field visibility
            document.getElementById('offer-modal').classList.remove('hidden');
        });
    }

    // Discount type change handler
    document.getElementById('offer-discount-type').addEventListener('change', toggleDiscountFields);

    // Cancel
    document.getElementById('cancel-offer').addEventListener('click', () => {
        document.getElementById('offer-modal').classList.add('hidden');
    });

    // Form submit
    document.getElementById('offer-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const id = document.getElementById('offer-id').value;
        const discountType = document.getElementById('offer-discount-type').value;
        const data = {
            name: document.getElementById('offer-name').value,
            code: document.getElementById('offer-code').value,
            description: document.getElementById('offer-description').value,
            discount_type: discountType,
            discount_value: discountType === 'time_bonus' ? null : (document.getElementById('offer-discount-amount').value || null),
            base_minutes: discountType === 'time_bonus' ? (document.getElementById('offer-base-minutes').value || null) : null,
            bonus_minutes: discountType === 'time_bonus' ? (document.getElementById('offer-bonus-minutes').value || null) : null,
            loop_bonus: discountType === 'time_bonus' ? (document.getElementById('offer-loop-bonus').checked ? 1 : 0) : 0,
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
                        document.getElementById('offer-base-minutes').value = coupon.base_minutes || '';
                        document.getElementById('offer-bonus-minutes').value = coupon.bonus_minutes || '';
                        document.getElementById('offer-loop-bonus').checked = coupon.loop_bonus == 1;
                        document.getElementById('offer-usage-limit').value = coupon.usage_limit;
                        document.getElementById('offer-min-order-amount').value = coupon.min_order_amount;
                        document.getElementById('offer-valid-from').value = coupon.valid_from;
                        document.getElementById('offer-valid-to').value = coupon.valid_to;
                        document.getElementById('offer-active').checked = coupon.status === 'Active';

                        // Toggle field visibility based on discount type
                        toggleDiscountFields();

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

<style>
    /* Enhanced Coupon Management Cards */
    .offers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }

    .coupon-card {
        background: 272929;
        border: 1px solid #444;
        border-radius: 12px;
        padding: 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .coupon-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
        border-color: #666;
    }

    .coupon-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #444;
    }

    /* Coupon Header */
    .coupon-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 20px 16px 20px;
        border-bottom: 1px solid #444;
    }

    .coupon-type-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #444;
        color: #d1d5db;
    }

    .type-icon {
        font-size: 16px;
    }

    .type-text {
        font-size: 11px;
    }

    .coupon-status {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #444;
        color: #d1d5db;
        border: 1px solid #666;
    }

    .status-inactive {
        background: #444;
        color: #d1d5db;
        border: 1px solid #666;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Coupon Content */
    .coupon-content {
        padding: 20px;
    }

    .coupon-name {
        font-size: 18px;
        font-weight: 700;
        color: #f3f4f6;
        margin: 0 0 16px 0;
        line-height: 1.3;
    }

    .coupon-code {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        padding: 12px 16px;
        background: #444;
        border: 2px dashed #666;
        border-radius: 8px;
    }

    .code-label {
        font-size: 12px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .code-value {
        font-family: 'Courier New', monospace;
        font-size: 14px;
        font-weight: 700;
        color: #f3f4f6;
        background: 272929;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid #666;
    }

    .coupon-details {
        margin-bottom: 20px;
    }

    .coupon-details p {
        margin: 0 0 8px 0;
        font-size: 14px;
        color: #9ca3af;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .coupon-details strong {
        color: #f3f4f6;
        font-weight: 600;
    }

    /* Usage Bar */
    .coupon-usage {
        margin-bottom: 20px;
    }

    .usage-bar {
        width: 100%;
        height: 8px;
        background: #444;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .usage-fill {
        height: 100%;
        background: #666;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .usage-text {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 600;
    }

    /* Coupon Actions */
    .coupon-actions {
        padding: 16px 20px 20px 20px;
        border-top: 1px solid #444;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .coupon-actions .btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .coupon-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-icon {
        font-size: 14px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .offers-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .coupon-card {
            margin: 0 8px;
        }

        .coupon-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }

        .coupon-actions {
            flex-direction: column;
        }

        .coupon-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }


    /* Animation for new cards */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .coupon-card {
        animation: slideInUp 0.3s ease-out;
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>