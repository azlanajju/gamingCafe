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
            <button id="bill-fandd-btn" class="btn btn--success">
                💰 Bill F&D
            </button>
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

<!-- F&D Billing Modal -->
<div id="fandd-billing-modal" class="modal hidden">
    <div class="modal-content billing-modal" style="max-width: 900px; width: 90%;">
        <div class="modal-header">
            <h3>Food & Drinks Billing</h3>
            <button class="modal-close-icon" onclick="closeFanddBillingModal()">&times;</button>
        </div>

        <div class="billing-content" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Left Side: Item Selection -->
            <div class="item-selection-section">
                <div class="form-group">
                    <label class="form-label">Search Items</label>
                    <input type="text" class="form-control" id="fandd-search-items" placeholder="Search by name or category..." onkeyup="filterFanddItems()">
                </div>
                <div class="form-group">
                    <label class="form-label">Category Filter</label>
                    <select class="form-control" id="fandd-category-filter" onchange="filterFanddItems()">
                        <option value="">All Categories</option>
                        <option value="beverages">Beverages</option>
                        <option value="snacks">Snacks</option>
                        <option value="meals">Meals</option>
                        <option value="desserts">Desserts</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="items-selection-list" id="fandd-items-selection" style="max-height: 400px; overflow-y: auto; border: 1px solid var(--color-border); border-radius: 8px; padding: 10px;">
                    <!-- Items will be loaded here -->
                </div>
            </div>

            <!-- Right Side: Cart & Billing -->
            <div class="cart-billing-section">
                <div class="customer-info-section">
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="fandd-customer-name" placeholder="Walk-in Customer" value="Walk-in Customer">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Customer Number (Optional)</label>
                        <input type="text" class="form-control" id="fandd-customer-number" placeholder="Phone number">
                    </div>
                </div>

                <div class="food-drinks-section">
                    <h4>Selected Items</h4>
                    <div id="fandd-billing-items" style="max-height: 200px; overflow-y: auto; margin-bottom: 15px;">
                        <p style="text-align: center; color: var(--color-text-secondary); padding: 20px;">No items selected</p>
                    </div>
                    <div class="food-drinks-total">
                        <div class="summary-line">
                            <span>Subtotal:</span> ₹<span id="fandd-subtotal">0.00</span>
                        </div>
                        <div class="summary-line">
                            <span>Tax (GST):</span> ₹<span id="fandd-tax">0.00</span>
                        </div>
                        <div class="grand-total">
                            <strong>Total: ₹<span id="fandd-total">0.00</span></strong>
                        </div>
                    </div>
                </div>

                <div class="payment-section">
                    <h4>Payment Method</h4>
                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="fandd-payment-method" value="cash" checked>
                            <span>Cash</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="fandd-payment-method" value="upi">
                            <span>UPI</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="fandd-payment-method" value="card">
                            <span>Card</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="fandd-payment-method" value="cash_upi">
                            <span>Cash + UPI</span>
                        </label>
                        <div id="fandd-cash-upi-split" class="split-inputs" style="display:none; gap:10px; margin-top:10px;">
                            <input type="number" id="fandd-cash-amount" class="form-control" placeholder="Cash amount" min="0" step="0.01">
                            <input type="number" id="fandd-upi-amount" class="form-control" placeholder="UPI amount" min="0" step="0.01">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="billing-modal-actions">
            <button type="button" class="btn btn--secondary" id="close-fandd-billing">Cancel</button>
            <button type="button" class="btn btn--primary" id="confirm-fandd-payment">Confirm Payment & Print</button>
        </div>
    </div>
</div>

<script>
    // Load branches
    function loadFanddBranches() {
        fetch(`${SITE_URL}/api/fandd.php?action=branches`)
            .then(res => res.json())
            .then(result => {
                console.log('Branches result:', result);
                if (result.success && result.data.length > 0) {
                    const itemSelect = document.getElementById('item-branch');

                    if (itemSelect) {
                        itemSelect.innerHTML = '<option value="">Select Branch</option>';
                    }

                    result.data.forEach(branch => {
                        if (itemSelect) {
                            const option1 = document.createElement('option');
                            option1.value = branch.id;
                            option1.textContent = `${branch.name} - ${branch.location}`;
                            itemSelect.appendChild(option1);
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
            // For Admins, use topbar branch selection or passed branch parameter
            const topbarBranchId = localStorage.getItem('selectedBranchId');
            const finalBranchId = topbarBranchId || branch;
            if (finalBranchId) params.push(`branch=${finalBranchId}`);
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
                            ${(USER_ROLE === 'Super Admin' || USER_ROLE === 'Admin' || USER_ROLE === 'Manager') ? `
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
        <?php if (Auth::isManagerRestricted()): ?>
            const userBranch = '<?php echo Auth::userBranchId() ?? 1; ?>';
            loadItems(category, userBranch);
        <?php else: ?>
            // Use topbar branch selection
            const topbarBranchId = localStorage.getItem('selectedBranchId');
            loadItems(category, topbarBranchId);
        <?php endif; ?>
    });

    // F&D Billing Cart
    let fanddCart = [];
    let allFanddItems = [];

    function showFanddBillingModal() {
        // Reset cart
        fanddCart = [];
        updateFanddBillingCart();

        // Reset form
        document.getElementById('fandd-customer-name').value = 'Walk-in Customer';
        document.getElementById('fandd-customer-number').value = '';
        document.getElementById('fandd-search-items').value = '';
        document.getElementById('fandd-category-filter').value = '';
        document.querySelector('input[name="fandd-payment-method"][value="cash"]').checked = true;
        document.getElementById('fandd-cash-upi-split').style.display = 'none';
        document.getElementById('fandd-cash-amount').value = '';
        document.getElementById('fandd-upi-amount').value = '';

        // Load items for selection
        loadFanddItemsForBilling();

        document.getElementById('fandd-billing-modal').classList.remove('hidden');
    }

    function loadFanddItemsForBilling() {
        let url = `${SITE_URL}/api/fandd.php?action=list`;
        <?php if (Auth::isManagerRestricted()): ?>
            url += `&branch=<?php echo Auth::userBranchId() ?? 1; ?>`;
        <?php else: ?>
            const topbarBranchId = localStorage.getItem('selectedBranchId');
            if (topbarBranchId) url += `&branch=${topbarBranchId}`;
        <?php endif; ?>

        fetch(url)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    allFanddItems = result.data.filter(item => item.is_available == 1 && item.stock > 0);
                    renderFanddItemsForSelection();
                }
            })
            .catch(error => {
                console.error('Error loading items:', error);
            });
    }

    function renderFanddItemsForSelection() {
        const container = document.getElementById('fandd-items-selection');
        const searchTerm = document.getElementById('fandd-search-items').value.toLowerCase();
        const categoryFilter = document.getElementById('fandd-category-filter').value;

        let filteredItems = allFanddItems.filter(item => {
            const matchesSearch = !searchTerm ||
                item.name.toLowerCase().includes(searchTerm) ||
                item.category.toLowerCase().includes(searchTerm);
            const matchesCategory = !categoryFilter || item.category === categoryFilter;
            return matchesSearch && matchesCategory;
        });

        if (filteredItems.length === 0) {
            container.innerHTML = '<p style="text-align: center; padding: 20px; color: var(--color-text-secondary);">No items found</p>';
            return;
        }

        container.innerHTML = filteredItems.map(item => `
            <div class="fandd-item-selection" style="display: flex; justify-content: space-between; align-items: center; padding: 12px; margin-bottom: 8px; border: 1px solid var(--color-border); border-radius: 8px; background: var(--color-surface);">
                <div style="flex: 1;">
                    <div style="font-weight: 600; margin-bottom: 4px;">${item.name}</div>
                    <div style="font-size: 12px; color: var(--color-text-secondary);">
                        ₹${parseFloat(item.price).toFixed(2)} | Stock: ${item.stock} | ${item.category}
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="number" 
                           id="qty-input-${item.id}" 
                           min="1" 
                           max="${item.stock}" 
                           value="1" 
                           style="width: 60px; padding: 6px; border: 1px solid var(--color-border); border-radius: 4px;">
                    <button class="btn btn--sm btn--success" onclick="addItemToFanddCart(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price}, ${item.stock})">
                        Add
                    </button>
                </div>
            </div>
        `).join('');
    }

    function filterFanddItems() {
        renderFanddItemsForSelection();
    }

    function addItemToFanddCart(itemId, itemName, itemPrice, stock) {
        const qtyInput = document.getElementById(`qty-input-${itemId}`);
        const quantity = parseInt(qtyInput.value) || 1;

        if (quantity <= 0) {
            alert('Quantity must be at least 1');
            return;
        }

        if (quantity > stock) {
            alert(`Only ${stock} items available in stock`);
            qtyInput.value = stock;
            return;
        }

        // Check if item already in cart
        const existingIndex = fanddCart.findIndex(item => item.id === itemId);
        if (existingIndex >= 0) {
            const newQty = fanddCart[existingIndex].quantity + quantity;
            if (newQty > stock) {
                alert(`Cannot add more. Only ${stock} items available in stock.`);
                return;
            }
            fanddCart[existingIndex].quantity = newQty;
        } else {
            fanddCart.push({
                id: itemId,
                name: itemName,
                price: itemPrice,
                quantity: quantity,
                stock: stock
            });
        }

        // Reset quantity input
        qtyInput.value = 1;
        updateFanddBillingCart();
    }

    function removeItemFromFanddCart(itemId) {
        fanddCart = fanddCart.filter(item => item.id !== itemId);
        updateFanddBillingCart();
    }

    function updateFanddCartQuantity(itemId, newQuantity) {
        const item = fanddCart.find(item => item.id === itemId);
        if (item) {
            if (newQuantity <= 0) {
                removeItemFromFanddCart(itemId);
            } else if (newQuantity > item.stock) {
                alert(`Only ${item.stock} items available in stock`);
                updateFanddBillingCart();
            } else {
                item.quantity = newQuantity;
                updateFanddBillingCart();
            }
        }
    }

    function updateFanddBillingCart() {
        const itemsList = document.getElementById('fandd-billing-items');

        if (fanddCart.length === 0) {
            itemsList.innerHTML = '<p style="text-align: center; color: var(--color-text-secondary); padding: 20px;">No items selected</p>';
            document.getElementById('fandd-subtotal').textContent = '0.00';
            document.getElementById('fandd-tax').textContent = '0.00';
            document.getElementById('fandd-total').textContent = '0.00';
            return;
        }

        let subtotal = 0;
        itemsList.innerHTML = fanddCart.map(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            return `
                <div class="billing-item-row" style="display: flex; justify-content: space-between; align-items: center; padding: 8px; margin-bottom: 8px; background: var(--color-bg-1); border-radius: 6px;">
                    <div style="flex: 1;">
                        <div style="font-weight: 600;">${item.name}</div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                            <button class="btn btn--xs" onclick="updateFanddCartQuantity(${item.id}, ${item.quantity - 1})" style="padding: 2px 8px;">-</button>
                            <span style="min-width: 30px; text-align: center;">${item.quantity}</span>
                            <button class="btn btn--xs" onclick="updateFanddCartQuantity(${item.id}, ${item.quantity + 1})" style="padding: 2px 8px;">+</button>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: var(--color-primary);">₹${itemTotal.toFixed(2)}</div>
                        <button class="btn btn--xs btn--danger" onclick="removeItemFromFanddCart(${item.id})" style="padding: 2px 8px; margin-top: 4px;">Remove</button>
                    </div>
                </div>
            `;
        }).join('');

        const taxRate = 0; // 0% GST
        const taxAmount = subtotal * taxRate;
        const totalAmount = subtotal + taxAmount;

        document.getElementById('fandd-subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('fandd-tax').textContent = taxAmount.toFixed(2);
        document.getElementById('fandd-total').textContent = totalAmount.toFixed(2);
    }

    function closeFanddBillingModal() {
        document.getElementById('fandd-billing-modal').classList.add('hidden');
    }

    function processFanddPayment() {
        if (fanddCart.length === 0) {
            alert('Please add items to bill');
            return;
        }

        const paymentMethod = document.querySelector('input[name="fandd-payment-method"]:checked').value;
        const cashAmount = parseFloat(document.getElementById('fandd-cash-amount').value) || 0;
        const upiAmount = parseFloat(document.getElementById('fandd-upi-amount').value) || 0;

        if (paymentMethod === 'cash_upi' && (cashAmount <= 0 || upiAmount <= 0)) {
            alert('Please enter both cash and UPI amounts');
            return;
        }

        // Calculate totals
        let subtotal = 0;
        const items = [];
        fanddCart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            items.push({
                fandd_item_id: item.id,
                item_name: item.name,
                quantity: item.quantity,
                unit_price: item.price,
                total_price: itemTotal
            });
        });

        const taxRate = 0;
        const taxAmount = subtotal * taxRate;
        const totalAmount = subtotal + taxAmount;

        const paymentDetails = paymentMethod === 'cash_upi' ? {
            cash: cashAmount,
            upi: upiAmount
        } : null;

        const paymentData = {
            customer_name: document.getElementById('fandd-customer-name').value.trim() || 'Walk-in Customer',
            customer_number: document.getElementById('fandd-customer-number').value.trim() || '',
            items: items,
            fandd_amount: subtotal,
            tax_amount: taxAmount,
            total_amount: totalAmount,
            final_amount: totalAmount,
            payment_method: paymentMethod,
            payment_details: paymentDetails,
            branch_id: <?php echo Auth::isManagerRestricted() ? (Auth::userBranchId() ?? 1) : 'null'; ?>
        };

        // Process payment
        fetch(`${SITE_URL}/api/fandd.php?action=process_billing`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(paymentData)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    alert('Payment processed successfully!');
                    printFanddReceipt(result.data);
                    closeFanddBillingModal();
                    // Clear cart
                    fanddCart = [];
                    // Reload items to update stock
                    loadItems(document.getElementById('category-filter').value);
                } else {
                    alert('Error processing payment: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error processing payment:', error);
                alert('Error processing payment. Please try again.');
            });
    }

    function printFanddReceipt(transactionData) {
        // Store transaction data for receipt generation
        window.currentFanddTransaction = transactionData;
        const printWindow = window.open('', '_blank');
        const receiptContent = generateFanddReceipt(transactionData);

        printWindow.document.write(`
            <html>
                <head>
                    <title>GameBot Gaming Cafe - F&D Receipt</title>
                    <style>
                        @media print {
                            body { margin: 0; padding: 10px; }
                        }
                        body { 
                            font-family: 'Courier New', 'Monaco', 'Lucida Console', monospace; 
                            margin: 0; 
                            padding: 20px; 
                            background: white;
                            color: #000;
                            font-size: 14px;
                            line-height: 1.4;
                        }
                        .receipt {
                            max-width: 400px;
                            margin: 0 auto;
                            background: white;
                        }
                        .receipt-header {
                            text-align: center;
                            margin-bottom: 15px;
                        }
                        .receipt-logo {
                            max-width: 80px;
                            height: auto;
                            margin-bottom: 10px;
                        }
                        .receipt-title {
                            font-size: 20px;
                            font-weight: bold;
                            margin: 10px 0;
                            text-transform: uppercase;
                            letter-spacing: 1px;
                        }
                        .receipt-divider {
                            border-top: 1px dashed #000;
                            margin: 10px 0;
                        }
                        .receipt-details {
                            margin-bottom: 15px;
                        }
                        .receipt-line {
                            margin: 3px 0;
                        }
                        .receipt-line span {
                            font-weight: bold;
                        }
                        .receipt-section {
                            margin: 15px 0;
                        }
                        .section-title {
                            font-size: 16px;
                            font-weight: bold;
                            margin: 10px 0 8px 0;
                            text-transform: uppercase;
                        }
                        .item-row {
                            display: flex;
                            justify-content: space-between;
                            margin: 5px 0;
                        }
                        .receipt-total {
                            text-align: center;
                            margin: 20px 0;
                        }
                        .grand-total {
                            font-size: 18px;
                            font-weight: bold;
                            margin: 0;
                            text-transform: uppercase;
                        }
                        .receipt-footer {
                            text-align: center;
                            margin-top: 20px;
                            font-style: italic;
                        }
                    </style>
                </head>
                <body>
                    ${receiptContent}
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.print();
    }

    function generateFanddReceipt(transactionData) {
        const currentDate = new Date().toLocaleDateString('en-IN');
        const currentTime = new Date().toLocaleTimeString('en-IN');
        const paymentMethod = transactionData.payment_method || 'cash';
        const transactionId = transactionData.transaction_id || 'N/A';
        const customerName = transactionData.customer_name || 'Walk-in Customer';
        const items = transactionData.items || [];
        const fanddAmount = parseFloat(transactionData.fandd_amount || 0);
        const taxAmount = parseFloat(transactionData.tax_amount || 0);
        const finalAmount = parseFloat(transactionData.final_amount || 0);

        return `
            <div class="receipt">
                <div class="receipt-header">
                    <img src="../assets/logo.png" alt="GameBot Gaming Cafe Logo" class="receipt-logo">
                    <h1 class="receipt-title">GameBot Gaming Cafe</h1>
                    <div class="receipt-divider"></div>
                </div>
                
                <div class="receipt-details">
                    <div class="receipt-line"><span>Transaction ID:</span> ${transactionId}</div>
                    <div class="receipt-line"><span>Customer:</span> ${customerName}</div>
                    <div class="receipt-line"><span>Date:</span> ${currentDate}</div>
                    <div class="receipt-line"><span>Time:</span> ${currentTime}</div>
                    <div class="receipt-line"><span>Type:</span> Food & Drinks Only</div>
                </div>

                <div class="receipt-section">
                    <h3 class="section-title">Items</h3>
                    ${items.map(item => {
                        const itemName = item.item_name || item.name || 'Unknown Item';
                        const quantity = item.quantity || 1;
                        const totalPrice = parseFloat(item.total_price || 0);
                        return `
                            <div class="item-row">
                                <span>${itemName} x ${quantity}</span>
                                <span>₹${totalPrice.toFixed(2)}</span>
                            </div>
                        `;
                    }).join('')}
                </div>

                <div class="receipt-section">
                    <div class="item-row"><span>Subtotal:</span> <span>₹${fanddAmount.toFixed(2)}</span></div>
                    <div class="item-row"><span>Tax (GST):</span> <span>₹${taxAmount.toFixed(2)}</span></div>
                    <div class="receipt-total">
                        <div class="grand-total">Total: ₹${finalAmount.toFixed(2)}</div>
                    </div>
                </div>

                <div class="receipt-section">
                    <div class="receipt-line"><span>Payment Method:</span> ${paymentMethod.toUpperCase()}</div>
                </div>

                <div class="receipt-footer">
                    <p>Thank you for your visit!</p>
                    <p>Visit us again soon!</p>
                </div>
            </div>
        `;
    }

    // Event listeners
    document.getElementById('bill-fandd-btn').addEventListener('click', showFanddBillingModal);
    document.getElementById('close-fandd-billing').addEventListener('click', closeFanddBillingModal);
    document.getElementById('confirm-fandd-payment').addEventListener('click', processFanddPayment);

    // Payment method change handler
    document.querySelectorAll('input[name="fandd-payment-method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const splitDiv = document.getElementById('fandd-cash-upi-split');
            if (this.value === 'cash_upi') {
                splitDiv.style.display = 'flex';
            } else {
                splitDiv.style.display = 'none';
            }
        });
    });

    // Make functions globally available
    window.addItemToFanddCart = addItemToFanddCart;
    window.removeItemFromFanddCart = removeItemFromFanddCart;
    window.updateFanddCartQuantity = updateFanddCartQuantity;
    window.filterFanddItems = filterFanddItems;

    // Initial load
    loadFanddBranches();

    // For Managers and Staff, automatically filter by their branch
    <?php if (Auth::isManagerRestricted()): ?>
        loadItems('', '<?php echo Auth::userBranchId() ?? 1; ?>');
    <?php else: ?>
        // For Admins, use topbar branch selection
        const topbarBranchId = localStorage.getItem('selectedBranchId');
        loadItems('', topbarBranchId);
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

    .fandd-item-selection:hover {
        background: var(--color-bg-2) !important;
        border-color: var(--color-primary) !important;
    }

    .btn--xs {
        padding: 4px 10px;
        font-size: 12px;
        border-radius: 4px;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    #bill-fandd-btn {
        background: #10b981 !important;
        color: white !important;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
        position: relative;
    }

    #bill-fandd-btn:hover {
        background: #059669 !important;
    }

    .billing-item-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--color-border);
    }

    .billing-item-name {
        flex: 1;
    }

    .billing-item-price {
        font-weight: 600;
        color: var(--color-primary);
    }

    .customer-info-section {
        margin-bottom: 20px;
        padding: 15px;
        background: var(--color-bg-1);
        border-radius: 8px;
    }

    .item-selection-section {
        border-right: 1px solid var(--color-border);
        padding-right: 20px;
    }

    .cart-billing-section {
        padding-left: 20px;
    }

    @media (max-width: 768px) {
        .billing-content {
            grid-template-columns: 1fr !important;
        }

        .item-selection-section {
            border-right: none;
            border-bottom: 1px solid var(--color-border);
            padding-right: 0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .cart-billing-section {
            padding-left: 0;
        }
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

<script>
    // Listen for topbar branch changes and filter F&D items
    window.addEventListener('branchChanged', function(event) {
        console.log('F&D management: Branch changed to:', event.detail);
        const selectedBranchId = event.detail.branchId;

        if (selectedBranchId) {
            // Filter F&D items by selected branch
            loadItems('', selectedBranchId);
        } else {
            // Show all F&D items
            loadItems('', '');
        }
    });

    // Ensure branch selection is restored on this page
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for branches to load, then restore selection
        setTimeout(function() {
            if (typeof window.restoreBranchSelection === 'function') {
                console.log('F&D management page: Attempting branch restoration...');
                window.restoreBranchSelection();
            }

            // Load F&D items based on current topbar selection
            const selectedBranchId = localStorage.getItem('selectedBranchId');
            if (selectedBranchId) {
                loadItems('', selectedBranchId);
            } else {
                loadItems('', '');
            }
        }, 1000);
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>