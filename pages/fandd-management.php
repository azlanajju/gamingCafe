<?php
$pageTitle = 'Food & Drinks Management';
$currentPage = 'fandd-management';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="fandd-management" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Food & Drinks Management</h2>
        <div class="header-actions">
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
            <div class="form-group">
                <label class="form-label">Item Name *</label>
                <input type="text" class="form-control" id="item-name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Price *</label>
                <input type="number" class="form-control" id="item-price" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stock *</label>
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
                <label class="checkbox-label">
                    <input type="checkbox" id="item-available" checked>
                    Available for purchase
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
    // Load items
    function loadItems(category = '') {
        const url = category ?
            `${SITE_URL}/api/fandd.php?action=list&category=${category}` :
            `${SITE_URL}/api/fandd.php?action=list`;

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
                        card.className = `item-card ${item.is_available ? 'available' : 'unavailable'}`;
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
        document.getElementById('item-available').checked = true;
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
                    document.getElementById('item-available').checked = item.is_available == 1;
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
            is_available: document.getElementById('item-available').checked ? 1 : 0
        };

        const url = isEdit ?
            `${SITE_URL}/api/fandd.php?action=update&id=${itemId}` :
            `${SITE_URL}/api/fandd.php?action=create`;
        const method = isEdit ? 'POST' : 'POST';

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
        loadItems(e.target.value);
    });

    // Initial load
    loadItems();
</script>

<style>
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .item-card {
        border: 1px solid #444;
        border-radius: 12px;
        padding: 20px;
        background: 272929;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }

    .item-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #444;
    }

    .item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
        border-color: #666;
    }

    .item-card.unavailable {
        opacity: 0.6;
        background: #1a1a1a;
    }

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .item-header h3 {
        margin: 0;
        color: #f3f4f6;
    }

    .item-status {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }

    .item-status.available {
        background: #444;
        color: #d1d5db;
    }

    .item-status.unavailable {
        background: #444;
        color: #d1d5db;
    }

    .item-body {
        margin-bottom: 15px;
    }

    .item-price {
        font-size: 18px;
        font-weight: bold;
        color: #d1d5db;
        margin: 0 0 8px 0;
    }

    .item-stock {
        color: #9ca3af;
        margin: 0 0 8px 0;
    }

    .item-category {
        color: #9ca3af;
        margin: 0 0 8px 0;
        text-transform: capitalize;
    }

    .item-description {
        color: #9ca3af;
        font-size: 14px;
        margin: 0;
        line-height: 1.4;
    }

    .item-actions {
        display: flex;
        gap: 10px;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .checkbox-label input[type="checkbox"] {
        margin: 0;
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>