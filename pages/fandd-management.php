<?php
$pageTitle = 'Food & Drinks Management';
$currentPage = 'fandd-management';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="fandd-management" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Food & Drinks Management</h2>
        <div class="header-actions">
            <?php if (Auth::hasRole('Admin')): ?>
                <select id="branch-filter" class="form-control" style="width: auto; display: inline-block;">
                    <option value="">All Branches</option>
                </select>
            <?php endif; ?>
            <select id="category-filter" class="form-control" style="width: auto; display: inline-block;">
                <option value="">All Categories</option>
                <option value="beverages">Beverages</option>
                <option value="snacks">Snacks</option>
                <option value="meals">Meals</option>
                <option value="desserts">Desserts</option>
                <option value="other">Other</option>
            </select>
            <?php if (Auth::hasRole('Admin') || Auth::hasRole('Manager')): ?>
                <button id="add-item-btn" class="btn btn--primary">Add Item</button>
            <?php endif; ?>
        </div>
    </div>

    <div class="items-grid" id="items-grid">
        <!-- Items will be loaded here dynamically -->
    </div>
</section>

<!-- Add/Edit Item Modal -->
<div id="item-modal" class="modal hidden">
    <div class="modal-content">
        <h3 id="item-modal-title">Add New Item</h3>
        <form id="item-form">
            <input type="hidden" id="item-id">
            <?php if (Auth::hasRole('Admin')): ?>
                <div class="form-group">
                    <label class="form-label">Branch *</label>
                    <select class="form-control" id="item-branch" required>
                        <option value="">Select Branch</option>
                    </select>
                </div>
            <?php else: ?>
                <input type="hidden" id="item-branch" value="<?php echo Auth::userBranchId() ?? 1; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label class="form-label">Item Name *</label>
                <input type="text" class="form-control" id="item-name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Price *</label>
                <input type="number" class="form-control" id="item-price" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stock Quantity *</label>
                <input type="number" class="form-control" id="item-stock" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category *</label>
                <select class="form-control" id="item-category" required>
                    <option value="">Select category</option>
                    <option value="beverages">Beverages</option>
                    <option value="snacks">Snacks</option>
                    <option value="meals">Meals</option>
                    <option value="desserts">Desserts</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control" id="item-description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" id="item-is-available" checked> Available
                </label>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="cancel-item">Cancel</button>
                <button type="submit" class="btn btn--primary">Save Item</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Load branches
    function loadBranches() {
        fetch(`${SITE_URL}/api/fandd.php?action=branches`)
            .then(res => res.json())
            .then(result => {
                console.log('Branches result:', result);
                if (result.success && result.data.length > 0) {
                    const itemSelect = document.getElementById('item-branch');
                    const filterSelect = document.getElementById('branch-filter');

                    if (itemSelect) {
                        itemSelect.innerHTML = '<option value="">Select Branch</option>';
                    }
                    if (filterSelect) {
                        filterSelect.innerHTML = '<option value="">All Branches</option>';
                    }

                    result.data.forEach(branch => {
                        if (itemSelect) {
                            const option1 = document.createElement('option');
                            option1.value = branch.id;
                            option1.textContent = `${branch.name} - ${branch.location}`;
                            itemSelect.appendChild(option1);
                        }

                        if (filterSelect) {
                            const option2 = document.createElement('option');
                            option2.value = branch.id;
                            option2.textContent = `${branch.name} - ${branch.location}`;
                            filterSelect.appendChild(option2);
                        }
                    });
                } else {
                    console.warn('No branches found or API error:', result);
                }
            })
            .catch(error => {
                console.error('Error loading branches:', error);
            });
    }

    // Load items with branch and category filtering
    function loadItems(category = '', branch = '') {
        let url = `${SITE_URL}/api/fandd.php?action=list`;
        const params = [];

        if (category) params.push(`category=${category}`);

        <?php if (Auth::isManagerRestricted()): ?>
            // For Managers and Staff, always filter by their branch
            params.push(`branch=<?php echo Auth::userBranchId() ?? 1; ?>`);
        <?php else: ?>
            if (branch) params.push(`branch=${branch}`);
        <?php endif; ?>

        if (params.length > 0) {
            url += '&' + params.join('&');
        }

        fetch(url)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const grid = document.getElementById('items-grid');
                    grid.innerHTML = '';

                    if (result.data.length === 0) {
                        grid.innerHTML = '<p style="text-align: center; padding: 20px;">No items found.</p>';
                        return;
                    }

                    result.data.forEach(item => {
                        const card = document.createElement('div');
                        const stockClass = item.stock < 10 ? 'low-stock' : '';
                        card.className = `item-card ${item.is_available ? 'available' : 'unavailable'} ${stockClass}`;
                        card.setAttribute('data-category', item.category.toLowerCase());
                        card.innerHTML = `
                            <div class="item-header">
                                <h3>${item.name}</h3>
                                <span class="item-status ${item.is_available ? 'available' : 'unavailable'}">
                                    ${item.is_available ? 'Available' : 'Unavailable'}
                                </span>
                            </div>
                            <div class="item-body">
                                <p class="item-price">₹${parseFloat(item.price).toFixed(2)}</p>
                                <p class="item-stock">Stock: ${item.stock}</p>
                                <p class="item-category">${item.category.charAt(0).toUpperCase() + item.category.slice(1)}</p>
                                <p class="item-branch">Branch: ${item.branch_name || 'Unknown'} - ${item.branch_location || 'Unknown'}</p>
                                ${item.description ? `<p class="item-description">${item.description}</p>` : ''}
                            </div>
                            ${USER_ROLE === 'Admin' || USER_ROLE === 'Manager' ? `
                            <div class="item-actions">
                                <button class="btn btn--sm btn--primary" onclick="editItem(${item.id})">Edit</button>
                                <button class="btn btn--sm btn--danger" onclick="deleteItem(${item.id})">Delete</button>
                            </div>
                            ` : ''}
                        `;
                        grid.appendChild(card);
                    });
                } else {
                    document.getElementById('items-grid').innerHTML =
                        '<p style="text-align: center; padding: 20px; color: red;">Error loading items: ' + result.message + '</p>';
                }
            })
            .catch(error => {
                console.error('Error loading items:', error);
                document.getElementById('items-grid').innerHTML =
                    '<p style="text-align: center; padding: 20px; color: red;">Network error loading items</p>';
            });
    }

    // Add item
    function addItem() {
        document.getElementById('item-modal-title').textContent = 'Add New Item';
        document.getElementById('item-form').reset();
        document.getElementById('item-modal').classList.remove('hidden');
    }

    // Edit item
    function editItem(id) {
        fetch(`${SITE_URL}/api/fandd.php?action=get&id=${id}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const item = result.data;
                    document.getElementById('item-modal-title').textContent = 'Edit Item';
                    document.getElementById('item-id').value = item.id;
                    document.getElementById('item-name').value = item.name;
                    document.getElementById('item-price').value = item.price;
                    document.getElementById('item-stock').value = item.stock;
                    document.getElementById('item-category').value = item.category;
                    document.getElementById('item-description').value = item.description || '';
                    document.getElementById('item-is-available').checked = item.is_available == 1;
                    const branchElement = document.getElementById('item-branch');
                    if (branchElement) {
                        branchElement.value = item.branch_id || '';
                    }
                    document.getElementById('item-modal').classList.remove('hidden');
                }
            });
    }

    // Delete item
    function deleteItem(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            fetch(`${SITE_URL}/api/fandd.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        loadItems(document.getElementById('category-filter').value);
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    }

    // Event listeners
    const addItemBtn = document.getElementById('add-item-btn');
    if (addItemBtn) {
        addItemBtn.addEventListener('click', addItem);
    }

    document.getElementById('item-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const itemId = document.getElementById('item-id').value;
        const isEdit = itemId !== '';

        const data = {
            name: document.getElementById('item-name').value.trim(),
            price: parseFloat(document.getElementById('item-price').value),
            stock: parseInt(document.getElementById('item-stock').value),
            category: document.getElementById('item-category').value,
            description: document.getElementById('item-description').value.trim(),
            is_available: document.getElementById('item-is-available').checked ? 1 : 0,
            branch_id: document.getElementById('item-branch').value
        };

        const url = isEdit ?
            `${SITE_URL}/api/fandd.php?action=update&id=${itemId}` :
            `${SITE_URL}/api/fandd.php?action=create`;
        const method = 'POST';

        fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    document.getElementById('item-modal').classList.add('hidden');
                    loadItems(document.getElementById('category-filter').value);
                } else {
                    alert('Error: ' + result.message);
                }
            });
    });

    document.getElementById('cancel-item').addEventListener('click', () => {
        document.getElementById('item-modal').classList.add('hidden');
    });

    document.getElementById('category-filter').addEventListener('change', (e) => {
        const category = e.target.value;
        const branchElement = document.getElementById('branch-filter');
        <?php if (Auth::isManagerRestricted()): ?>
            const userBranch = '<?php echo Auth::userBranchId() ?? 1; ?>';
            loadItems(category, userBranch);
        <?php else: ?>
            const selectedBranch = branchElement ? branchElement.value : '';
            loadItems(category, selectedBranch);
        <?php endif; ?>
    });

    const branchFilter = document.getElementById('branch-filter');
    if (branchFilter) {
        branchFilter.addEventListener('change', (e) => {
            const branch = e.target.value;
            const category = document.getElementById('category-filter').value;
            loadItems(category, branch);
        });
    }

    // Initial load
    loadBranches();

    // For Managers and Staff, automatically filter by their branch
    <?php if (Auth::isManagerRestricted()): ?>
        loadItems('', '<?php echo Auth::userBranchId() ?? 1; ?>');
    <?php else: ?>
        loadItems();
    <?php endif; ?>
</script>

<style>
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .item-card {
        border: 1px solid var(--color-border);
        border-radius: 16px;
        padding: 0;
        background: var(--color-surface);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        backdrop-filter: blur(10px);
    }

    .item-card::after {
        content: '🍕';
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 32px;
        opacity: 0.1;
        transition: all 0.3s ease;
    }

    .item-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: var(--shadow-lg);
        border-color: var(--color-primary);
    }

    .item-card:hover::after {
        opacity: 0.3;
        transform: rotate(15deg) scale(1.2);
    }

    .item-card.unavailable {
        opacity: 0.7;
        background: var(--color-bg-1);
        filter: grayscale(0.3);
    }

    .item-card.unavailable::after {
        content: '❌';
    }

    .item-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-bottom: 1px solid var(--color-border);
    }

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 24px 24px 20px 24px;
        border-bottom: 1px solid var(--color-border);
        background: linear-gradient(135deg, var(--color-bg-1) 0%, var(--color-bg-2) 100%);
        position: relative;
        z-index: 2;
    }

    .item-header h3 {
        margin: 0;
        color: var(--color-text);
        font-size: 18px;
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -0.02em;
        flex: 1;
        padding-right: 16px;
    }

    .item-status {
        padding: 6px 14px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: var(--shadow-xs);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .item-status.available {
        background: var(--color-success);
        color: var(--color-white);
    }

    .item-status.available::before {
        content: '✅';
        font-size: 14px;
    }

    .item-status.unavailable {
        background: var(--color-error);
        color: var(--color-white);
    }

    .item-status.unavailable::before {
        content: '❌';
        font-size: 14px;
    }

    .item-body {
        padding: 24px;
        background: var(--color-surface);
    }

    .item-price {
        font-size: 24px;
        font-weight: 800;
        color: var(--color-primary);
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--color-bg-1);
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: var(--shadow-xs);
    }

    .item-price::before {
        content: '💰';
        font-size: 20px;
    }

    .item-stock {
        color: var(--color-text-secondary);
        margin: 0 0 12px 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: var(--color-bg-2);
        border-radius: 8px;
        border: 1px solid var(--color-border);
    }

    .item-stock::before {
        content: '📦';
        font-size: 16px;
    }

    .item-category {
        color: var(--color-text);
        margin: 0 0 16px 0;
        text-transform: capitalize;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: var(--color-warning);
        color: var(--color-white);
        border-radius: 20px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .item-category::before {
        content: '🏷️';
        font-size: 14px;
    }

    .item-description {
        color: var(--color-text-secondary);
        font-size: 14px;
        margin: 12px 0 0 0;
        line-height: 1.5;
        background: var(--color-bg-1);
        padding: 12px;
        border-radius: 8px;
        border: 1px solid var(--color-border);
        font-style: italic;
    }

    .item-branch {
        color: var(--color-text-secondary);
        font-size: 13px;
        margin: 12px 0 0 0;
    }

    .item-actions {
        display: flex;
        gap: 12px;
        padding: 20px 24px 24px 24px;
        border-top: 1px solid var(--color-border);
        background: var(--color-bg-1);
        justify-content: flex-end;
    }

    .item-actions .btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .item-actions .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .item-actions .btn:hover::before {
        left: 100%;
    }

    .item-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .item-actions .btn--primary:hover {
        background: var(--color-primary-hover);
    }

    .item-actions .btn--danger:hover {
        background: var(--color-error);
        transform: translateY(-2px) scale(1.05);
    }

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    /* Enhanced Animations and Effects */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }

        100% {
            background-position: calc(200px + 100%) 0;
        }
    }

    .item-card {
        animation: slideInUp 0.4s ease-out;
    }

    .item-card:nth-child(even) {
        animation-delay: 0.1s;
    }

    .item-card:nth-child(3n) {
        animation-delay: 0.2s;
    }

    .item-card.loading {
        background: linear-gradient(90deg, var(--color-surface) 25%, var(--color-bg-1) 50%, var(--color-surface) 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    .item-card:hover {
        box-shadow: var(--shadow-lg), 0 0 20px rgba(var(--color-primary-rgb, 59, 130, 246), 0.15);
    }

    .item-card.low-stock {
        border-color: var(--color-warning);
    }

    .item-card.low-stock .item-stock {
        color: var(--color-warning);
        font-weight: 700;
    }

    .item-card.low-stock .item-stock::before {
        content: '⚠️';
    }

    /* Category-specific styling */
    .item-card[data-category="beverages"] .item-category {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .item-card[data-category="beverages"]::after {
        content: '🥤';
    }

    .item-card[data-category="snacks"] .item-category {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .item-card[data-category="snacks"]::after {
        content: '🍿';
    }

    .item-card[data-category="meals"] .item-category {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .item-card[data-category="meals"]::after {
        content: '🍽️';
    }

    .item-card[data-category="desserts"] .item-category {
        background: linear-gradient(135deg, #ec4899, #db2777);
    }

    .item-card[data-category="desserts"]::after {
        content: '🍰';
    }

    .item-card[data-category="other"] .item-category {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .item-card[data-category="other"]::after {
        content: '🎁';
    }

    /* Enhanced grid layout */
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
        margin-top: 24px;
        padding: 8px;
    }

    /* Responsive enhancements */
    @media (max-width: 768px) {
        .items-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .item-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }

        .item-actions {
            flex-direction: column;
        }

        .item-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>