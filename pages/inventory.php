<?php
$pageTitle = 'Inventory Management';
$currentPage = 'inventory';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="inventory" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Inventory Management</h2>
        <button class="btn btn--primary" id="add-inventory-btn">Add New Item</button>
    </div>
    <div class="inventory-grid" id="inventory-grid">
        <!-- Inventory items will be loaded here -->
    </div>
</section>

<!-- Add Inventory Modal -->
<div id="add-inventory-modal" class="modal hidden">
    <div class="modal-content">
        <h3 id="inventory-modal-title">Add New Item</h3>
        <form id="inventory-form">
            <input type="hidden" id="inventory-id">
            <div class="form-group">
                <label class="form-label">Item Name *</label>
                <input type="text" class="form-control" id="inventory-name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category *</label>
                <select class="form-control" id="inventory-category" required>
                    <option value="">Select category</option>
                    <option value="Beverages">Beverages</option>
                    <option value="Snacks">Snacks</option>
                    <option value="Food">Food</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Cost Price *</label>
                <input type="number" class="form-control" id="inventory-cost" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Selling Price *</label>
                <input type="number" class="form-control" id="inventory-selling" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stock Quantity *</label>
                <input type="number" class="form-control" id="inventory-stock" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Expiry Date</label>
                <input type="date" class="form-control" id="inventory-expiry">
            </div>
            <div class="form-group">
                <label class="form-label">Supplier</label>
                <input type="text" class="form-control" id="inventory-supplier">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="cancel-inventory">Cancel</button>
                <button type="submit" class="btn btn--primary">Save Item</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Load inventory
    function loadInventory() {
        fetch(`${SITE_URL}/api/inventory.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const grid = document.getElementById('inventory-grid');
                    grid.innerHTML = '';

                    result.data.forEach(item => {
                        const card = document.createElement('div');
                        card.className = 'inventory-card card';
                        const stockClass = item.stock_quantity < 10 ? 'low-stock' : '';
                        card.innerHTML = `
                        <h3>${item.name}</h3>
                        <p><strong>Category:</strong> ${item.category}</p>
                        <p><strong>Selling Price:</strong> ₹${parseFloat(item.selling_price).toFixed(2)}</p>
                        <p class="${stockClass}"><strong>Stock:</strong> ${item.stock_quantity}</p>
                        <div class="card-actions">
                            <button class="btn btn--sm btn--primary" onclick="editInventory(${item.id})">Edit</button>
                            <button class="btn btn--sm btn--danger" onclick="deleteInventory(${item.id})">Delete</button>
                        </div>
                    `;
                        grid.appendChild(card);
                    });
                }
            });
    }

    // Add inventory button
    document.getElementById('add-inventory-btn').addEventListener('click', () => {
        document.getElementById('inventory-modal-title').textContent = 'Add New Item';
        document.getElementById('inventory-form').reset();
        document.getElementById('inventory-id').value = '';
        document.getElementById('add-inventory-modal').classList.remove('hidden');
    });

    // Cancel
    document.getElementById('cancel-inventory').addEventListener('click', () => {
        document.getElementById('add-inventory-modal').classList.add('hidden');
    });

    // Form submit
    document.getElementById('inventory-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const id = document.getElementById('inventory-id').value;
        const data = {
            name: document.getElementById('inventory-name').value,
            category: document.getElementById('inventory-category').value,
            cost_price: document.getElementById('inventory-cost').value,
            selling_price: document.getElementById('inventory-selling').value,
            stock_quantity: document.getElementById('inventory-stock').value,
            expiry_date: document.getElementById('inventory-expiry').value || null,
            supplier: document.getElementById('inventory-supplier').value || null,
            branch_id: 1
        };

        const action = id ? 'update' : 'create';
        if (id) data.id = id;

        fetch(`${SITE_URL}/api/inventory.php?action=${action}`, {
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
                    document.getElementById('add-inventory-modal').classList.add('hidden');
                    loadInventory();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    });

    // Edit inventory
    function editInventory(id) {
        fetch(`${SITE_URL}/api/inventory.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const item = result.data.find(i => i.id == id);
                    if (item) {
                        document.getElementById('inventory-modal-title').textContent = 'Edit Item';
                        document.getElementById('inventory-id').value = item.id;
                        document.getElementById('inventory-name').value = item.name;
                        document.getElementById('inventory-category').value = item.category;
                        document.getElementById('inventory-cost').value = item.cost_price;
                        document.getElementById('inventory-selling').value = item.selling_price;
                        document.getElementById('inventory-stock').value = item.stock_quantity;
                        document.getElementById('inventory-expiry').value = item.expiry_date;
                        document.getElementById('inventory-supplier').value = item.supplier;
                        document.getElementById('add-inventory-modal').classList.remove('hidden');
                    }
                }
            });
    }

    // Delete inventory
    function deleteInventory(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            fetch(`${SITE_URL}/api/inventory.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        loadInventory();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    }

    // Initial load
    loadInventory();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

