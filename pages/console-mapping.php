<?php
$pageTitle = 'Console Management';
$currentPage = 'console-mapping';
require_once __DIR__ . '/../includes/header.php';
?>
<style>
    button:disabled {
        color: grey !important;
        cursor: not-allowed;
        opacity: 0.6;
    }
</style>
<section id="console-mapping" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Console Management</h2>
        <button id="add-console-btn" class="btn btn--primary">Add Console</button>
    </div>
    <div class="console-grid" id="console-grid">
        <!-- Consoles will be loaded here dynamically -->
    </div>
</section>

<!-- Add Console Modal -->
<div id="add-console-modal" class="modal hidden">
    <div class="modal-content">
        <h3 id="console-modal-title">Add New Console</h3>
        <form id="console-form">
            <input type="hidden" id="console-id">
            <div class="form-group">
                <label class="form-label">Console Name *</label>
                <input type="text" class="form-control" id="console-name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Console Type *</label>
                <select class="form-control" id="console-type" required>
                    <option value="">Select console type</option>
                    <option value="PC">PC</option>
                    <option value="PS5">PlayStation 5</option>
                    <option value="Xbox">Xbox Series X/S</option>
                    <option value="Nintendo Switch">Nintendo Switch</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Specifications</label>
                <input type="text" class="form-control" id="console-specs">
            </div>
            <div class="form-group">
                <label class="form-label">Purchase Year *</label>
                <input type="number" class="form-control" id="console-year" min="2020" max="2025" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" class="form-control" id="console-email" required>
            </div>
            <div class="form-group">
                <label class="form-label">Primary User *</label>
                <input type="text" class="form-control" id="console-user" required>
            </div>
            <div class="form-group">
                <label class="form-label">Location *</label>
                <select class="form-control" id="console-location" required>
                    <option value="">Select location</option>
                    <option value="Zone A">Zone A</option>
                    <option value="Zone B">Zone B</option>
                    <option value="Zone C">Zone C</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Account Type</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" id="console-plus"> Plus Account</label>
                    <label><input type="checkbox" id="console-maintenance"> Under Maintenance</label>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="cancel-console">Cancel</button>
                <button type="submit" class="btn btn--primary">Save Console</button>
            </div>
        </form>
    </div>
</div>

<!-- Start Gaming Session Modal -->
<div id="start-session-modal" class="modal hidden">
    <div class="modal-content">
        <h3 id="session-modal-title">Start Gaming Session</h3>
        <form id="start-session-form">
            <input type="hidden" id="console-id-session">
            <div class="form-group">
                <label class="form-label">Customer Name *</label>
                <input type="text" class="form-control" id="customer-name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Customer Number</label>
                <input type="tel" class="form-control" id="customer-number">
            </div>
            <div class="form-group">
                <label class="form-label">Number of Players *</label>
                <select class="form-control" id="player-count" required>
                    <option value="1">1 Player</option>
                    <option value="2">2 Players</option>
                    <option value="3">3 Players</option>
                    <option value="4">4 Players</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Rate Type *</label>
                <div class="radio-group">
                    <label><input type="radio" name="rate-type" value="regular" checked> Regular</label>
                    <label><input type="radio" name="rate-type" value="vip"> VIP</label>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="cancel-session">Cancel</button>
                <button type="submit" class="btn btn--primary">Start Session</button>
            </div>
        </form>
    </div>
</div>

<!-- Transfer Session Modal -->
<div id="transfer-session-modal" class="modal hidden">
    <div class="modal-content">
        <h3>Transfer Session</h3>
        <input type="hidden" id="transfer-console-id">
        <div class="form-group">
            <label class="form-label">Select Target Console</label>
            <div id="available-consoles-list" class="transfer-list">
                <!-- Available consoles will be loaded here -->
            </div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn--secondary" id="cancel-transfer">Cancel</button>
            <button type="button" class="btn btn--primary" id="confirm-transfer-btn">Transfer Session</button>
        </div>
    </div>
</div>

<!-- Change Players Modal -->
<div id="change-players-modal" class="modal hidden">
    <div class="modal-content">
        <h3>Change Number of Players</h3>
        <input type="hidden" id="change-players-console-id">
        <div class="form-group">
            <label class="form-label">New Number of Players *</label>
            <select class="form-control" id="new-player-count" required>
                <option value="1">1 Player</option>
                <option value="2">2 Players</option>
                <option value="3">3 Players</option>
                <option value="4">4 Players</option>
            </select>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn--secondary" id="cancel-change-players">Cancel</button>
            <button type="button" class="btn btn--primary" id="confirm-change-players-btn">Update Players</button>
        </div>
    </div>
</div>

<!-- Add F&D Modal -->
<div id="add-fandd-modal" class="modal hidden">
    <div class="modal-content fandd-modal">
        <h3>Add Food & Drinks</h3>
        <input type="hidden" id="add-fandd-console-id">

        <div class="fandd-items-container">
            <div id="fandd-items-grid" class="fandd-items-grid">
                <!-- Items will be loaded here -->
            </div>
        </div>

        <div class="fandd-modal-actions">
            <div class="fandd-selected-summary">
                No items selected
            </div>
            <div class="fandd-action-buttons">
                <button type="button" class="btn btn--secondary" id="cancel-add-fandd">Cancel</button>
                <button type="button" class="btn btn--primary" id="confirm-add-fandd-btn">Add Selected Items</button>
            </div>
        </div>
    </div>
</div>

<!-- Session Billing Modal -->
<div id="session-billing-modal" class="modal hidden">
    <div class="modal-content billing-modal">
        <div class="modal-header">
            <h3>Session Billing</h3>
            <button class="modal-close-icon" onclick="closeBillingModal()">&times;</button>
        </div>

        <div class="billing-content">
            <div class="bill-header">
                <div class="bill-logo">
                    <img src="../assets/logo.png" alt="Gaming Cafe Logo" class="logo-img">
                </div>

            </div>

            <div class="customer-info">
                <p><strong>Customer Name:</strong> <span id="billing-customer-name"></span></p>
                <p><strong>Total Duration:</strong> <span id="billing-duration"></span></p>
                <p><strong>Session Date:</strong> <span id="billing-date"></span></p>
            </div>

            <div class="gaming-segments">
                <h4>Gaming Segments</h4>
                <div id="gaming-segments-list">
                    <!-- Gaming segments will be loaded here -->
                </div>
                <div class="pause-history">
                    <p><strong>Pause History:</strong> <span id="pause-history">No pauses for this session.</span></p>
                </div>
                <div class="gaming-total">
                    <strong>Gaming Total: ₹<span id="gaming-total-amount"></span></strong>
                </div>
            </div>

            <div class="food-drinks-section">
                <h4>Food & Drink Charges</h4>
                <div id="food-drinks-list">
                    <!-- Food & drink items will be loaded here -->
                </div>
                <div class="food-drinks-total">
                    <strong>Food & Drinks Total: ₹<span id="food-drinks-total-amount"></span></strong>
                </div>
            </div>

            <div class="discounts-section">
                <h4>Discounts</h4>
                <div class="coupon-section">
                    <label for="coupon-code">Apply Coupon:</label>
                    <div class="coupon-input-group">
                        <select id="coupon-code" class="form-control">
                            <option value="">No coupon</option>
                        </select>
                        <button type="button" class="btn btn--sm btn--secondary" onclick="applyCoupon()">Apply</button>
                    </div>
                    <p><strong>Discount:</strong> ₹<span id="discount-amount">0.00</span></p>
                </div>
            </div>

            <div class="grand-total">
                <strong>Grand Total: ₹<span id="grand-total-amount"></span></strong>
            </div>

            <div class="payment-section">
                <h4>Payment Method</h4>
                <div class="payment-methods">
                    <label class="payment-option">
                        <input type="radio" name="payment-method" value="cash" checked>
                        <span>Cash</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment-method" value="upi">
                        <span>UPI</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment-method" value="card">
                        <span>Card</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment-method" value="cash_upi">
                        <span>Cash + UPI</span>
                    </label>
                    <div id="cash-upi-split" class="split-inputs" style="display:none; gap:10px; margin-top:10px;">
                        <input type="number" id="cash-amount" class="form-control" placeholder="Cash amount" min="0" step="0.01">
                        <input type="number" id="upi-amount" class="form-control" placeholder="UPI amount" min="0" step="0.01">
                    </div>
                </div>
            </div>
        </div>

        <div class="billing-modal-actions">
            <button type="button" class="btn btn--secondary" onclick="closeBillingModal()">Cancel</button>
            <button type="button" class="btn btn--primary" onclick="confirmPaymentAndPrint()">Confirm Payment & Print</button>
        </div>
    </div>
</div>

<script>
    // Load consoles
    function loadConsoles() {
        console.log('Loading consoles from:', `${SITE_URL}/api/consoles.php?action=list`);

        const grid = document.getElementById('console-grid');
        grid.innerHTML = '<p style="text-align: center; padding: 20px;">Loading consoles...</p>';

        fetch(`${SITE_URL}/api/consoles.php?action=list`)
            .then(res => {
                console.log('Response status:', res.status);
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(result => {
                console.log('API Response:', result);

                if (result.success) {
                    const grid = document.getElementById('console-grid');
                    grid.innerHTML = '';

                    if (result.data.length === 0) {
                        grid.innerHTML = '<p style="text-align: center; padding: 20px;">No consoles found. Click "Add Console" to create one.</p>';
                        return;
                    }

                    consolesData = result.data; // Store fetched data globally

                    result.data.forEach(console => {
                        const card = document.createElement('div');
                        // Determine card class based on maintenance status and current session
                        let cardClass = console.status.toLowerCase();

                        // Check if console has an active session (occupied)
                        if (console.current_session) {
                            cardClass = 'occupied';
                            card.setAttribute('data-session-id', console.current_session.id);
                        }

                        // Override with maintenance if under maintenance
                        if (console.under_maintenance == 1 || console.under_maintenance === true) {
                            cardClass = 'maintenance';
                        }

                        card.className = `console-card ${cardClass}`;
                        card.setAttribute('data-console-id', console.id);
                        card.setAttribute('data-type', console.type.toLowerCase());

                        let sessionContent = '';
                        let actionButtons = '';
                        let consoleIcons = '';

                        // Console icons (edit and delete) - always shown
                        consoleIcons = `
                            <div class="console-icons">
                                <button class="icon-btn edit-btn" onclick="editConsole(${console.id})" title="Edit Console"                ${cardClass === 'occupied' ? 'disabled' : ''}>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button class="icon-btn delete-btn" onclick="deleteConsole(${console.id})" title="Delete Console"  ${cardClass === 'occupied' ? 'disabled' : ''}>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3,6 5,6 21,6"></polyline>
                                        <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </div>
                        `;

                        if (console.current_session) {
                            const session = console.current_session;

                            // Get session items for display
                            const sessionItems = session.items || session.fandd_items || [];
                            let itemsDisplay = '';
                            if (sessionItems.length > 0) {
                                itemsDisplay = `
                                    <div class="session-items">
                                        <div class="items-label">Items:</div>
                                        ${sessionItems.map(item => `
                                            <div class="session-item">
                                                <span>${item.name || item.item_name} (${item.quantity})</span>
                                                <button class="remove-item-btn" onclick="removeSessionItem(${console.id}, ${item.id})" title="Remove Item">✕</button>
                                            </div>
                                        `).join('')}
                                    </div>
                                `;
                            } else {
                                // Show placeholder for items even if none exist
                                itemsDisplay = `
                                    <div class="session-items">
                                        <div class="items-label">Items:</div>
                                        <div class="session-item">
                                            <span>No items added</span>
                                        </div>
                                    </div>
                                `;
                            }

                            sessionContent = `
                                <div class="occupied-session">
                                    <div class="session-timer" id="timer-${console.id}">${session.formatted_time || '00:00:00'}</div>
                                    <div class="session-details">
                                        <p><strong>Customer:</strong> ${session.customer_name}</p>
                                        <p><strong>Current Players:</strong> ${session.player_count}</p>
                                        <p><strong>Rate Type:</strong> ${session.rate_type.charAt(0).toUpperCase() + session.rate_type.slice(1)}</p>
                                        <p><strong>Started:</strong> ${session.start_time_formatted || 'Unknown'}</p>
                                        ${itemsDisplay}
                                    </div>
                                </div>
                            `;

                            actionButtons = `
                                <div class="session-controls">
                                    <div class="top-buttons">
                                        <button class="btn btn--sm btn--secondary" onclick="showAddFandDModal(${console.id})">Add F&D</button>
                                        <button class="btn btn--sm btn--secondary" onclick="showTransferModal(${console.id})">Transfer</button>
                                        <button class="btn btn--sm btn--secondary" onclick="showChangePlayersModal(${console.id})">Change Players</button>
                                        ${session.is_paused ? 
                                            `<button class="btn btn--sm btn--secondary" onclick="resumeSession(${console.id})">▶ Resume</button>` :
                                            `<button class="btn btn--sm btn--secondary" onclick="pauseSession(${console.id})">⏸ Pause</button>`
                                        }
                                    </div>
                                    <div class="bottom-buttons">
                                        <button class="btn btn--lg btn--danger" onclick="endSession(${console.id})">End Session</button>
                                    </div>
                                </div>
                            `;
                        } else if (console.status.toLowerCase() === 'available') {
                            actionButtons = `
                                <button class="btn btn--primary btn--start" onclick="showStartSessionModal(${console.id})">Start Session</button>
                            `;
                        }

                        // Check if console is under maintenance from database
                        if (console.under_maintenance == 1 || console.under_maintenance === true) {
                            sessionContent = `
                                <div class="maintenance-content">
                                    <div class="maintenance-text">MAINTENANCE</div>
                                </div>
                            `;
                            actionButtons = '';
                        }

                        // Get user role for display
                        const userRole = console.primary_user || 'System Admin';
                        const membershipType = console.has_plus_account ? 'Plus Membership' : 'No Membership';

                        // Get console type icon
                        const getConsoleIcon = (type) => {
                            const icons = {
                                'PC': '🖥️',
                                'PS5': '🎮',
                                'PlayStation 5': '🎮',
                                'Xbox': '🎯',
                                'Xbox Series X/S': '🎯',
                                'Nintendo Switch': '🕹️'
                            };
                            return icons[type] || '🎮';
                        };

                        // Get status display info
                        const getStatusInfo = () => {
                            if (console.under_maintenance == 1 || console.under_maintenance === true) {
                                return {
                                    text: 'MAINTENANCE',
                                    icon: '🔧'
                                };
                            } else if (console.current_session) {
                                return {
                                    text: 'OCCUPIED',
                                    icon: '🔴'
                                };
                            } else {
                                return {
                                    text: 'AVAILABLE',
                                    icon: '🟢'
                                };
                            }
                        };

                        const statusInfo = getStatusInfo();
                        const consoleIcon = getConsoleIcon(console.type);

                        card.innerHTML = `
                            <div class="console-card-header">
                                <div class="console-title-section">
                                    <div class="console-icon">${consoleIcon}</div>
                                    <div class="console-title-info">
                                        <h3 class="console-name">${console.name}</h3>
                                        <div class="console-type-badge">${console.type}</div>
                                    </div>
                                </div>
                                <div class="console-status-section">
                                    <div class="console-status status-${cardClass}">
                                        <span class="status-icon">${statusInfo.icon}</span>
                                        <span class="status-text">${statusInfo.text}</span>
                                    </div>
                                    ${consoleIcons}
                                </div>
                            </div>
                            
                            <div class="console-info-section">
                                <div class="console-specs-card">
                                    <div class="spec-item">
                                        <span class="spec-icon">⚙️</span>
                                        <span class="spec-text">${console.specifications || `${console.type}, ${console.purchase_year}`}</span>
                                    </div>
  
                                    <div class="spec-item">
                                        <span class="spec-icon">👤</span>
                                        <span class="spec-text">${userRole}</span>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-icon">${console.has_plus_account ? '⭐' : '👤'}</span>
                                        <span class="spec-text">${membershipType}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="console-content-section">
                                ${sessionContent}
                            </div>
                            
                            <div class="console-actions">
                                ${actionButtons}
                            </div>
                        `;
                        grid.appendChild(card);

                        // Start timer if session is active
                        if (console.current_session && !console.current_session.is_paused) {
                            startSessionTimer(console.id, console.current_session.id);
                        }
                    });
                } else {
                    grid.innerHTML = `<p style="text-align: center; padding: 20px; color: red;">Error: ${result.message || 'Failed to load consoles'}</p>`;
                }
            })
            .catch(error => {
                console.error('Error loading consoles:', error);
                grid.innerHTML = `<p style="text-align: center; padding: 20px; color: red;">Network Error: ${error.message}. Please check your connection and try again.</p>`;
            });
    }

    // Add console button
    document.getElementById('add-console-btn').addEventListener('click', () => {
        document.getElementById('console-modal-title').textContent = 'Add New Console';
        document.getElementById('console-form').reset();
        document.getElementById('console-id').value = '';
        document.getElementById('add-console-modal').classList.remove('hidden');
    });

    // Cancel button
    document.getElementById('cancel-console').addEventListener('click', () => {
        document.getElementById('add-console-modal').classList.add('hidden');
    });

    // Form submit
    document.getElementById('console-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const id = document.getElementById('console-id').value;
        const data = {
            name: document.getElementById('console-name').value,
            type: document.getElementById('console-type').value,
            specifications: document.getElementById('console-specs').value,
            purchase_year: document.getElementById('console-year').value,
            email: document.getElementById('console-email').value,
            primary_user: document.getElementById('console-user').value,
            location: document.getElementById('console-location').value,
            has_plus_account: document.getElementById('console-plus').checked ? 1 : 0,
            under_maintenance: document.getElementById('console-maintenance').checked ? 1 : 0,
            branch_id: 1
        };

        const action = id ? 'update' : 'create';
        if (id) data.id = id;

        fetch(`${SITE_URL}/api/consoles.php?action=${action}`, {
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
                    document.getElementById('add-console-modal').classList.add('hidden');
                    loadConsoles();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    });

    // Edit console
    function editConsole(id) {
        fetch(`${SITE_URL}/api/consoles.php?action=get&id=${id}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const console = result.data;
                    document.getElementById('console-modal-title').textContent = 'Edit Console';
                    document.getElementById('console-id').value = console.id;
                    document.getElementById('console-name').value = console.name;
                    document.getElementById('console-type').value = console.type;
                    document.getElementById('console-specs').value = console.specifications;
                    document.getElementById('console-year').value = console.purchase_year;
                    document.getElementById('console-email').value = console.email;
                    document.getElementById('console-user').value = console.primary_user;
                    document.getElementById('console-location').value = console.location;
                    document.getElementById('console-plus').checked = console.has_plus_account == 1;
                    document.getElementById('console-maintenance').checked = (console.under_maintenance == 1 || console.under_maintenance === true);
                    document.getElementById('add-console-modal').classList.remove('hidden');
                }
            });
    }

    // Delete console
    function deleteConsole(id) {
        if (confirm('Are you sure you want to delete this console?')) {
            fetch(`${SITE_URL}/api/consoles.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        loadConsoles();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    }

    // Session Management
    let activeSessions = {};
    let sessionTimers = {};
    let consolesData = []; // Global variable to store console data

    // Start Gaming Session Modal
    function showStartSessionModal(consoleId) {
        document.getElementById('session-modal-title').textContent = 'Start Gaming Session';
        document.getElementById('start-session-form').reset();
        document.getElementById('console-id-session').value = consoleId;
        document.getElementById('start-session-modal').classList.remove('hidden');
    }

    // Start Session
    function startSession() {
        const consoleId = document.getElementById('console-id-session').value;
        const customerName = document.getElementById('customer-name').value.trim();
        const customerNumber = document.getElementById('customer-number').value.trim();
        const playerCount = parseInt(document.getElementById('player-count').value);
        const rateType = document.querySelector('input[name="rate-type"]:checked').value;

        if (!customerName) {
            alert('Please enter customer name');
            return;
        }

        const sessionData = {
            console_id: consoleId,
            customer_name: customerName,
            customer_number: customerNumber,
            player_count: playerCount,
            rate_type: rateType,
            start_time: new Date().toISOString(),
            timezone_offset: '+05:30' // UTC+5:30
        };

        fetch(`${SITE_URL}/api/sessions.php?action=start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(sessionData)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    alert('Session started successfully');
                    document.getElementById('start-session-modal').classList.add('hidden');
                    loadConsoles();
                    startSessionTimer(consoleId, result.data.session_id);
                } else {
                    alert('Error: ' + result.message);
                }
            });
    }

    // Start Session Timer
    function startSessionTimer(consoleId, sessionId) {
        if (sessionTimers[consoleId]) {
            clearInterval(sessionTimers[consoleId]);
        }

        sessionTimers[consoleId] = setInterval(() => {
            updateSessionTimer(consoleId);
        }, 1000);
    }

    // Update Session Timer with UTC+5:30 offset
    function updateSessionTimer(consoleId) {
        const timerElement = document.querySelector(`#timer-${consoleId}`);
        if (!timerElement) return;

        fetch(`${SITE_URL}/api/sessions.php?action=get_time&console_id=${consoleId}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    timerElement.textContent = result.data.formatted_time;
                }
            });
    }

    // Pause Session
    function pauseSession(consoleId) {
        fetch(`${SITE_URL}/api/sessions.php?action=pause`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    console_id: consoleId
                })
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    alert('Session paused');
                    // Stop any existing timer for this console
                    if (sessionTimers[consoleId]) {
                        clearInterval(sessionTimers[consoleId]);
                        delete sessionTimers[consoleId];
                    }
                    loadConsoles();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    }

    // Resume Session
    function resumeSession(consoleId) {
        fetch(`${SITE_URL}/api/sessions.php?action=resume`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    console_id: consoleId
                })
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    alert('Session resumed');
                    // Start timer for resumed session
                    loadConsoles();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    }

    // End Session
    function endSession(consoleId) {
        if (confirm('Are you sure you want to end this session?')) {
            const consoleCard = document.querySelector(`.console-card[data-console-id="${consoleId}"]`);
            if (!consoleCard) {
                alert('Console card not found.');
                return;
            }
            const sessionId = consoleCard.dataset.sessionId; // Get the session ID from the card

            if (!sessionId) {
                alert('No active session ID found for this console.');
                return;
            }

            // Call the new API to get pre-billing details without ending the session
            fetch(`${SITE_URL}/api/sessions.php?action=get_pre_billing_details&session_id=${sessionId}`)
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        // Populate currentBillingData with actual data from backend
                        currentBillingData = result.data;
                        showBillingModal(result.data);
                    } else {
                        alert('Error fetching billing details: ' + result.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching pre-billing details:', error);
                    alert('Error fetching pre-billing details. Please try again.');
                });
        }
    }

    // Helper to get active session ID (no longer used, but kept for reference if needed elsewhere)
    function getActiveSessionId(consoleId) {
        const consoleCard = document.querySelector(`.console-card.occupied[data-console-id="${consoleId}"]`);
        if (consoleCard && consoleCard.dataset.sessionId) {
            return consoleCard.dataset.sessionId;
        }
        return null; // Return null if not found
    }

    // Transfer Session
    function showTransferModal(consoleId) {
        document.getElementById('transfer-console-id').value = consoleId;

        // Load available consoles
        fetch(`${SITE_URL}/api/consoles.php?action=list&status=available`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const list = document.getElementById('available-consoles-list');
                    list.innerHTML = '';

                    result.data.forEach(console => {
                        const item = document.createElement('div');
                        item.className = 'console-item';
                        item.innerHTML = `
                        <input type="radio" name="target-console" value="${console.id}" id="target-${console.id}">
                        <label for="target-${console.id}">
                            <strong>${console.name}</strong> - ${console.type} (${console.location})
                        </label>
                    `;
                        list.appendChild(item);
                    });

                    document.getElementById('transfer-session-modal').classList.remove('hidden');
                }
            });
    }

    // Confirm Transfer
    function confirmTransfer() {
        const sourceConsoleId = document.getElementById('transfer-console-id').value;
        const targetConsoleId = document.querySelector('input[name="target-console"]:checked')?.value;

        if (!targetConsoleId) {
            alert('Please select a target console');
            return;
        }

        const transferData = {
            source_console_id: sourceConsoleId,
            target_console_id: targetConsoleId
        };

        fetch(`${SITE_URL}/api/sessions.php?action=transfer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(transferData)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    alert('Session transferred successfully');
                    document.getElementById('transfer-session-modal').classList.add('hidden');

                    // Update timers
                    if (sessionTimers[sourceConsoleId]) {
                        clearInterval(sessionTimers[sourceConsoleId]);
                        delete sessionTimers[sourceConsoleId];
                    }
                    startSessionTimer(targetConsoleId, result.data.session_id);

                    loadConsoles();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    }

    // Change Players
    function showChangePlayersModal(consoleId) {
        document.getElementById('change-players-console-id').value = consoleId;
        document.getElementById('change-players-modal').classList.remove('hidden');
    }

    // Confirm Change Players
    function confirmChangePlayers() {
        const consoleId = document.getElementById('change-players-console-id').value;
        const newPlayerCount = parseInt(document.getElementById('new-player-count').value);

        const changeData = {
            console_id: consoleId,
            new_player_count: newPlayerCount
        };

        fetch(`${SITE_URL}/api/sessions.php?action=change_players`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(changeData)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    alert('Player count updated successfully and new segment started');
                    document.getElementById('change-players-modal').classList.add('hidden');
                    loadConsoles();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    }

    // Add F&D (Food & Drinks)
    function showAddFandDModal(consoleId) {
        document.getElementById('add-fandd-console-id').value = consoleId;
        loadFandDItems();
        document.getElementById('add-fandd-modal').classList.remove('hidden');
    }

    // Load F&D items for the modal
    function loadFandDItems() {
        const grid = document.getElementById('fandd-items-grid');
        grid.innerHTML = '<div class="loading-message">Loading delicious items...</div>';

        fetch(`${SITE_URL}/api/fandd.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    grid.innerHTML = '';

                    result.data.forEach((item, index) => {
                        if (item.is_available && item.stock > 0) {
                            const itemCard = document.createElement('div');
                            const stockClass = item.stock < 10 ? 'low-stock' : '';
                            itemCard.className = 'fandd-item-card';
                            itemCard.setAttribute('data-category', item.category.toLowerCase());
                            itemCard.style.animationDelay = `${index * 0.1}s`;

                            itemCard.innerHTML = `
                                <div class="fandd-item-header">
                                    <div class="fandd-item-name">${item.name}</div>
                                    <div class="fandd-item-category">${item.category.charAt(0).toUpperCase() + item.category.slice(1)}</div>
                                </div>
                                <div class="fandd-item-body">
                                    <div class="fandd-item-price">₹${parseFloat(item.price).toFixed(2)}</div>
                                    <div class="fandd-item-stock ${stockClass}">Stock: ${item.stock}</div>
                                    <div class="fandd-quantity-controls">
                                        <button type="button" class="fandd-qty-btn fandd-qty-minus" onclick="adjustQuantity(${item.id}, -1)" title="Decrease quantity">-</button>
                                        <input type="number" class="fandd-qty-input" id="qty-${item.id}" value="0" min="0" max="${item.stock}" readonly>
                                        <button type="button" class="fandd-qty-btn fandd-qty-plus" onclick="adjustQuantity(${item.id}, 1, ${item.stock})" title="Increase quantity">+</button>
                                    </div>
                                </div>
                                <input type="hidden" class="fandd-item-data" data-id="${item.id}" data-name="${item.name}" data-price="${item.price}">
                            `;

                            // Add click handler for card selection
                            itemCard.addEventListener('click', (e) => {
                                if (!e.target.matches('.fandd-qty-btn, .fandd-qty-input')) {
                                    const qtyInput = itemCard.querySelector('.fandd-qty-input');
                                    const currentQty = parseInt(qtyInput.value);
                                    if (currentQty === 0) {
                                        adjustQuantity(item.id, 1, item.stock);
                                    }
                                }
                            });

                            grid.appendChild(itemCard);
                        }
                    });

                    // Update the summary
                    updateFandDSummary();
                } else {
                    grid.innerHTML = '<div class="error-message">Failed to load items. Please try again.</div>';
                }
            })
            .catch(error => {
                console.error('Error loading F&D items:', error);
                grid.innerHTML = '<div class="error-message">Network error. Please check your connection.</div>';
            });
    }

    // Enhanced adjust quantity for F&D items
    function adjustQuantity(itemId, change, maxStock) {
        const input = document.getElementById(`qty-${itemId}`);
        const card = input.closest('.fandd-item-card');
        const minusBtn = card.querySelector('.fandd-qty-minus');
        const plusBtn = card.querySelector('.fandd-qty-plus');

        let currentValue = parseInt(input.value) || 0;
        let newValue = currentValue + change;

        if (newValue < 0) newValue = 0;
        if (newValue > maxStock) newValue = maxStock;

        input.value = newValue;

        // Update button states
        minusBtn.disabled = newValue <= 0;
        plusBtn.disabled = newValue >= maxStock;

        // Update card selection state
        if (newValue > 0) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }

        // Update summary
        updateFandDSummary();
    }

    // Update F&D selection summary
    function updateFandDSummary() {
        const summaryElement = document.querySelector('.fandd-selected-summary');
        if (!summaryElement) return;

        let totalItems = 0;
        let totalValue = 0;

        document.querySelectorAll('.fandd-item-data').forEach(element => {
            const itemId = element.dataset.id;
            const quantity = parseInt(document.getElementById(`qty-${itemId}`).value) || 0;
            const price = parseFloat(element.dataset.price);

            if (quantity > 0) {
                totalItems += quantity;
                totalValue += quantity * price;
            }
        });

        if (totalItems > 0) {
            summaryElement.innerHTML = `
                 ${totalItems} item${totalItems > 1 ? 's' : ''} selected - ₹${totalValue.toFixed(2)}
            `;
            summaryElement.style.display = 'flex';
        } else {
            summaryElement.innerHTML = ' No items selected';
            summaryElement.style.display = 'flex';
        }
    }

    // Confirm Add F&D
    function confirmAddFandD() {
        const consoleId = document.getElementById('add-fandd-console-id').value;
        const selectedItems = [];

        // Collect all items with quantity > 0
        document.querySelectorAll('.fandd-item-data').forEach(element => {
            const itemId = element.dataset.id;
            const quantity = parseInt(document.getElementById(`qty-${itemId}`).value);

            if (quantity > 0) {
                selectedItems.push({
                    id: itemId,
                    name: element.dataset.name,
                    price: parseFloat(element.dataset.price),
                    quantity: quantity
                });
            }
        });

        if (selectedItems.length === 0) {
            alert('Please select at least one item');
            return;
        }

        // Add each item to the session
        let promises = selectedItems.map(item => {
            const fanddData = {
                console_id: consoleId,
                item_name: item.name,
                quantity: item.quantity,
                price: item.price
            };

            return fetch(`${SITE_URL}/api/sessions.php?action=add_fandd`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(fanddData)
            }).then(res => res.json());
        });

        // Wait for all items to be added
        Promise.all(promises)
            .then(results => {
                const successCount = results.filter(result => result.success).length;
                if (successCount === selectedItems.length) {
                    alert(`Successfully added ${selectedItems.length} item(s) to the session`);
                    document.getElementById('add-fandd-modal').classList.add('hidden');
                    loadConsoles();
                } else {
                    alert('Some items could not be added. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error adding F&D items:', error);
                alert('Error adding items. Please try again.');
            });
    }

    // Remove Session Item
    function removeSessionItem(consoleId, itemId) {
        if (confirm('Are you sure you want to remove this item from the session?')) {
            const removeData = {
                console_id: consoleId,
                item_id: itemId
            };

            fetch(`${SITE_URL}/api/sessions.php?action=remove_fandd`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(removeData)
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        alert('Item removed successfully');
                        loadConsoles();
                    } else {
                        alert('Error: ' + result.message);
                    }
                })
                .catch(error => {
                    console.error('Error removing item:', error);
                    alert('Error removing item. Please try again.');
                });
        }
    }

    // Global variables for billing
    let currentBillingData = null;
    let appliedCoupon = null;

    // Show Billing Modal
    function showBillingModal(billingData) {
        currentBillingData = billingData;

        // Populate customer info
        document.getElementById('billing-customer-name').textContent = billingData.customer_name || 'Unknown';

        // Format duration
        const hours = Math.floor(billingData.duration_minutes / 60);
        const minutes = billingData.duration_minutes % 60;
        const seconds = 0; // We don't have seconds in the data
        document.getElementById('billing-duration').textContent =
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        // Set current date
        const currentDate = new Date().toLocaleDateString('en-IN');
        document.getElementById('billing-date').textContent = currentDate;

        // Populate gaming segments
        const gamingSegmentsList = document.getElementById('gaming-segments-list');
        gamingSegmentsList.innerHTML = ''; // Clear existing content

        if (billingData.segments && billingData.segments.length > 0) {
            billingData.segments.forEach(segment => {
                // Calculate segment duration for display
                let segmentDurationMinutes = segment.duration; // Use already calculated duration if available
                if (!segment.end_time) {
                    // If it's the active segment, calculate duration from start_time to now
                    const start = new Date(segment.start_time);
                    const now = new Date();
                    segmentDurationMinutes = Math.round((now.getTime() - start.getTime()) / (1000 * 60));
                }

                const segmentHours = Math.floor(segmentDurationMinutes / 60);
                const segmentMinutes = segmentDurationMinutes % 60;
                const segmentSeconds = 0; // Assuming duration is in minutes

                const formattedSegmentDuration =
                    `${segmentHours.toString().padStart(2, '0')}:${segmentMinutes.toString().padStart(2, '0')}:${segmentSeconds.toString().padStart(2, '0')}`;

                gamingSegmentsList.innerHTML += `
                    <div class="segment-item">
                        <span>${segment.player_count} players (${formattedSegmentDuration})</span>
                        <span>₹${parseFloat(segment.calculated_amount || 0).toFixed(2)}</span>
                    </div>
                `;
            });
        } else {
            gamingSegmentsList.innerHTML = '<p>No gaming segments recorded</p>';
        }

        document.getElementById('pause-history').textContent = 'No pauses for this session.'; // Placeholder, update if pause history is available
        document.getElementById('gaming-total-amount').textContent = parseFloat(billingData.gaming_amount || 0).toFixed(2);

        // Populate food & drinks
        const foodDrinksList = document.getElementById('food-drinks-list');
        if (billingData.fandd_items && billingData.fandd_items.length > 0) {
            foodDrinksList.innerHTML = billingData.fandd_items.map(item => `
                <div class="food-drink-item">
                    <span>${item.name} (${item.quantity})</span>
                    <span>₹${parseFloat(item.total_price || 0).toFixed(2)}</span>
                </div>
            `).join('');
        } else {
            foodDrinksList.innerHTML = '<p>No items added</p>';
        }

        // Load available coupons
        loadAvailableCoupons();

        // Reset payment method to default
        document.querySelector('input[name="payment-method"][value="cash"]').checked = true;
        document.getElementById('cash-upi-split').style.display = 'none';
        document.getElementById('cash-amount').value = '';
        document.getElementById('upi-amount').value = '';

        // Populate amounts
        updateBillingAmounts();

        // Show modal
        document.getElementById('session-billing-modal').classList.remove('hidden');
    }

    // Toggle split inputs visibility based on payment method
    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'payment-method') {
            const method = e.target.value;
            const split = document.getElementById('cash-upi-split');
            if (method === 'cash_upi') {
                split.style.display = 'flex';
            } else {
                split.style.display = 'none';
            }
        }
    });

    // Close Billing Modal
    function closeBillingModal() {
        document.getElementById('session-billing-modal').classList.add('hidden');
    }

    // Load Available Coupons
    function loadAvailableCoupons() {
        fetch(`${SITE_URL}/api/coupons.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const couponSelect = document.getElementById('coupon-code');
                    couponSelect.innerHTML = '<option value="">No coupon</option>';

                    console.log('Loaded coupons:', result.data);

                    result.data.forEach(coupon => {
                        // Only show active coupons
                        if (coupon.status === 'Active') {
                            const option = document.createElement('option');
                            option.value = coupon.code; // Use coupon code as value
                            option.setAttribute('data-coupon-data', JSON.stringify(coupon)); // Store full coupon data

                            // Format display text based on discount type
                            let displayText = `${coupon.code}`;
                            if (coupon.discount_type === 'percentage') {
                                displayText += ` - ${coupon.discount_value}% off`;
                            } else if (coupon.discount_type === 'flat') {
                                displayText += ` - ₹${coupon.discount_value} off`;
                            }
                            option.textContent = displayText;

                            couponSelect.appendChild(option);
                        }
                    });
                } else {
                    console.error('Failed to load coupons:', result.message);
                }
            })
            .catch(error => {
                console.error('Error loading coupons:', error);
            });
    }

    // Apply Coupon
    function applyCoupon() {
        const couponSelect = document.getElementById('coupon-code');
        const couponCode = couponSelect.value;

        if (!couponCode) {
            appliedCoupon = null;
            updateBillingAmounts();
            return;
        }

        // Use ONLY gaming amount for coupon validation
        const gamingAmount = parseFloat(currentBillingData.gaming_amount || 0);
        const currentTotal = gamingAmount;

        // Validate coupon with the API
        fetch(`${SITE_URL}/api/coupons.php?action=validate&code=${couponCode}&amount=${currentTotal}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    appliedCoupon = result.data;
                    updateBillingAmounts();

                    // Show success message
                    let discountText = '';
                    if (appliedCoupon.discount_type === 'percentage') {
                        discountText = `${appliedCoupon.discount_value}% discount (₹${appliedCoupon.calculated_discount.toFixed(2)})`;
                    } else if (appliedCoupon.discount_type === 'flat') {
                        discountText = `₹${appliedCoupon.discount_value} discount`;
                    }
                    alert(`Coupon applied successfully! ${discountText}`);
                } else {
                    alert(result.message || 'Invalid or expired coupon');
                }
            })
            .catch(error => {
                console.error('Error applying coupon:', error);
                alert('Error applying coupon');
            });
    }

    // Update Billing Amounts
    function updateBillingAmounts() {
        if (!currentBillingData) return;

        const gamingAmount = parseFloat(currentBillingData.gaming_amount || 0);
        const fanddAmount = parseFloat(currentBillingData.fandd_amount || 0);
        const subtotal = gamingAmount + fanddAmount;

        let discountAmount = 0;
        if (appliedCoupon) {
            // Calculate discount based on coupon type
            // Coupons apply ONLY to gaming amount
            if (appliedCoupon.discount_type === 'percentage') {
                discountAmount = (gamingAmount * appliedCoupon.discount_value) / 100;
            } else if (appliedCoupon.discount_type === 'flat') {
                discountAmount = Math.min(appliedCoupon.discount_value, gamingAmount);
            }

            // Use the calculated discount from API if available
            if (appliedCoupon.calculated_discount !== undefined) {
                discountAmount = appliedCoupon.calculated_discount;
            }
        }

        const grandTotal = (gamingAmount - discountAmount) + fanddAmount;

        document.getElementById('gaming-total-amount').textContent = gamingAmount.toFixed(2);
        document.getElementById('food-drinks-total-amount').textContent = fanddAmount.toFixed(2);
        document.getElementById('discount-amount').textContent = discountAmount.toFixed(2);
        document.getElementById('grand-total-amount').textContent = grandTotal.toFixed(2);
    }

    // Confirm Payment and Print
    function confirmPaymentAndPrint() {
        const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;

        if (!currentBillingData) {
            alert('No billing data available');
            return;
        }

        const grandTotal = parseFloat(document.getElementById('grand-total-amount').textContent);
        let paymentDetails = null;
        if (paymentMethod === 'cash_upi') {
            const cash = parseFloat(document.getElementById('cash-amount').value || '0');
            const upi = parseFloat(document.getElementById('upi-amount').value || '0');
            const sum = Number((cash + upi).toFixed(2));
            if (sum !== Number(grandTotal.toFixed(2))) {
                alert(`Cash + UPI must equal Grand Total (₹${grandTotal.toFixed(2)}). Currently ₹${sum.toFixed(2)}.`);
                return;
            }
            paymentDetails = {
                cash_amount: cash,
                upi_amount: upi
            };
        }

        // Prepare payment data
        const paymentData = {
            session_id: currentBillingData.session_id,
            payment_method: paymentMethod,
            coupon_code: appliedCoupon ? appliedCoupon.code : null,
            // Send the calculated amounts from the frontend to the backend
            discount_amount: parseFloat(document.getElementById('discount-amount').textContent),
            final_amount: parseFloat(document.getElementById('grand-total-amount').textContent),
            gaming_amount: parseFloat(document.getElementById('gaming-total-amount').textContent),
            fandd_amount: parseFloat(document.getElementById('food-drinks-total-amount').textContent),
            tax_amount: (parseFloat(document.getElementById('grand-total-amount').textContent) -
                parseFloat(document.getElementById('gaming-total-amount').textContent) -
                parseFloat(document.getElementById('food-drinks-total-amount').textContent) +
                parseFloat(document.getElementById('discount-amount').textContent)).toFixed(2),
            payment_details: paymentDetails
        };

        console.log('Payment Data:', paymentData);

        // First, call the 'end' action to finalize the session and get server-side calculated billing
        fetch(`${SITE_URL}/api/sessions.php?action=end`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    console_id: currentBillingData.console_id
                }) // Only console_id is needed for 'end'
            })
            .then(res => res.json())
            .then(endSessionResult => {
                if (endSessionResult.success) {
                    // Now that the session is officially 'ended' on backend, process payment
                    // Use the billing data returned from the 'end' action if needed for precise amounts,
                    // but for now, we'll continue using client-calculated amounts for consistency with paymentData structure.
                    // If backend calculation is preferred, replace paymentData amounts with endSessionResult.data amounts.

                    // Proceed with processing payment
                    fetch(`${SITE_URL}/api/sessions.php?action=process_payment`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(paymentData)
                        })
                        .then(res => res.json())
                        .then(paymentResult => {
                            if (paymentResult.success) {
                                alert('Payment processed successfully!');
                                printReceipt();
                                closeBillingModal();
                                if (sessionTimers[currentBillingData.console_id]) {
                                    clearInterval(sessionTimers[currentBillingData.console_id]);
                                    delete sessionTimers[currentBillingData.console_id];
                                }
                                loadConsoles();
                            } else {
                                alert('Error processing payment: ' + paymentResult.message);
                                // Potentially revert session end if payment fails? (Complex, might be handled by manual admin intervention)
                            }
                        })
                        .catch(paymentError => {
                            console.error('Error processing payment:', paymentError);
                            alert('Error processing payment. Please try again.');
                        });
                } else {
                    alert('Error finalizing session: ' + endSessionResult.message);
                }
            })
            .catch(endSessionError => {
                console.error('Error finalizing session:', endSessionError);
                alert('Error finalizing session. Please try again.');
            });
    }

    // Print Receipt
    function printReceipt() {
        // Create a printable version of the bill
        const printWindow = window.open('', '_blank');
        const billContent = generatePrintableBill();

        printWindow.document.write(`
            <html>
                <head>
                    <title>GameBot Gaming Cafe - Session Receipt</title>
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
                        
                        .segment-item {
                            margin: 8px 0;
                        }
                        
                        .segment-header {
                            font-weight: bold;
                            margin-bottom: 5px;
                        }
                        
                        .segment-details {
                            margin-left: 15px;
                        }
                        
                        .segment-line {
                            margin: 2px 0;
                        }
                        
                        .receipt-summary {
                            margin: 15px 0;
                        }
                        
                        .summary-line {
                            margin: 3px 0;
                        }
                        
                        .summary-line span {
                            font-weight: bold;
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
                        
                        .receipt-footer p {
                            margin: 0;
                        }
                    </style>
                </head>
                <body>
                    ${billContent}
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.print();
    }

    // Generate Printable Bill
    function generatePrintableBill() {
        if (!currentBillingData) return '';

        const hours = Math.floor(currentBillingData.duration_minutes / 60);
        const minutes = currentBillingData.duration_minutes % 60;
        const currentDate = new Date().toLocaleDateString('en-IN');
        const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;
        const transactionId = currentBillingData.session_id || Math.floor(Math.random() * 1000);
        const staffName = 'Branch Admin'; // You can get this from session or make it dynamic

        return `
            <div class="receipt">
                <div class="receipt-header">
                    <img src="../assets/logo.png" alt="GameBot Gaming Cafe Logo" class="receipt-logo">
                    <h1 class="receipt-title">GameBot Gaming Cafe</h1>
                    <div class="receipt-divider"></div>
                </div>
                
                <div class="receipt-details">
                    <div class="receipt-line"><span>Transaction ID:</span> ${transactionId}</div>
                    <div class="receipt-line"><span>Customer:</span> ${currentBillingData.customer_name || 'Unknown'}</div>
                    <div class="receipt-line"><span>Console:</span> Gaming PC Beta</div>
                    <div class="receipt-line"><span>Rate Type:</span> ${(currentBillingData.rate_type || 'REGULAR').toUpperCase()}</div>
                    <div class="receipt-line"><span>Total Duration:</span> ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:00</div>
                </div>

                <div class="receipt-section">
                    <h3 class="section-title">Gaming Segments:</h3>
                    <div class="segment-item">
                        <div class="segment-header">Segment 1: ${currentBillingData.player_count || 1} players (${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:00)</div>
                        <div class="segment-details">
                            <div class="segment-line">Base Rate: ₹${parseFloat(currentBillingData.gaming_amount || 0).toFixed(2)}</div>
                            <div class="segment-line">Multipliers: None</div>
                            <div class="segment-line">Final: ₹${parseFloat(currentBillingData.gaming_amount || 0).toFixed(2)}</div>
                        </div>
                    </div>
                </div>

                <div class="receipt-summary">
                    <div class="summary-line">
                        <span>Gaming Total:</span> ₹${parseFloat(currentBillingData.gaming_amount || 0).toFixed(2)}
                    </div>
                    <div class="summary-line">
                        <span>Food & Drinks:</span> ₹${parseFloat(currentBillingData.fandd_amount || 0).toFixed(2)}
                    </div>
                    <div class="summary-line">
                        <span>Payment Method:</span> ${paymentMethod.charAt(0).toUpperCase() + paymentMethod.slice(1)}
                    </div>
                    <div class="summary-line">
                        <span>Staff:</span> ${staffName}
                    </div>
                    <div class="receipt-divider"></div>
                    <div class="summary-line">
                        <span>Coupon Used:</span> ${appliedCoupon ? appliedCoupon.code : 'None'}
                    </div>
                </div>

                <div class="receipt-total">
                    <h2 class="grand-total">Grand Total: ₹${document.getElementById('grand-total-amount').textContent}</h2>
                </div>

                <div class="receipt-footer">
                    <p>Thank you for choosing GameBot Gaming Cafe!</p>
                </div>
            </div>
        `;
    }

    // Event Listeners for Session Management
    document.getElementById('start-session-form').addEventListener('submit', (e) => {
        e.preventDefault();
        startSession();
    });

    document.getElementById('cancel-session').addEventListener('click', () => {
        document.getElementById('start-session-modal').classList.add('hidden');
    });

    document.getElementById('cancel-transfer').addEventListener('click', () => {
        document.getElementById('transfer-session-modal').classList.add('hidden');
    });

    document.getElementById('confirm-transfer-btn').addEventListener('click', confirmTransfer);

    document.getElementById('cancel-change-players').addEventListener('click', () => {
        document.getElementById('change-players-modal').classList.add('hidden');
    });

    document.getElementById('confirm-change-players-btn').addEventListener('click', confirmChangePlayers);

    document.getElementById('cancel-add-fandd').addEventListener('click', () => {
        document.getElementById('add-fandd-modal').classList.add('hidden');
    });

    document.getElementById('confirm-add-fandd-btn').addEventListener('click', confirmAddFandD);

    // Initial load
    loadConsoles();
</script>

<style>
    /* Console Mapping Page - Dark Theme */
    body {
        background-color: var(--color-background) !important;
    }

    .main-content {
        background-color: var(--color-background) !important;
    }

    .section-header {
        background-color: var(--color-background) !important;
    }

    .section-title {
        color: var(--color-text) !important;
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }

    #add-console-btn {
        background: var(--color-primary) !important;
        color: var(--color-btn-primary-text) !important;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    #add-console-btn:hover {
        background: var(--color-primary-hover) !important;
    }

    /* Console Grid */
    .console-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 24px;
    }

    /* Enhanced Console Cards */
    .console-card {
        background: var(--color-surface) !important;
        border: 1px solid var(--color-border);
        border-radius: 12px;
        padding: 0;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 300px;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }



    .console-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--color-primary);
    }

    .console-card.occupied {
        border-color: var(--color-warning);
        background: linear-gradient(135deg, var(--color-surface) 0%, rgba(var(--color-warning-rgb), 0.03) 100%) !important;
        animation: pulse-occupied 3s infinite;
    }

    .console-card.out-of-service {
        border-color: var(--color-error);
        background: var(--color-surface) !important;
        opacity: 0.7;
        filter: grayscale(0.4);
    }

    /* Enhanced Console Card Header */
    .console-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 16px 16px 12px 16px;
        background: linear-gradient(135deg, var(--color-bg-1) 0%, var(--color-bg-2) 100%);
        border-bottom: 1px solid var(--color-border);
        position: relative;
    }

    .console-title-section {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .console-icon {
        font-size: 24px;
        padding: 8px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: var(--shadow-xs);
        transition: all 0.3s ease;
    }

    .console-card:hover .console-icon {
        transform: rotate(10deg) scale(1.1);
        box-shadow: var(--shadow-sm);
    }

    .console-title-info {
        flex: 1;
    }

    .console-name {
        color: var(--color-text) !important;
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 6px 0;
        line-height: 1.2;
        letter-spacing: -0.01em;
        background: transparent !important;
    }

    .console-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        background: var(--color-primary);
        color: var(--color-white);
        border-radius: 16px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        box-shadow: var(--shadow-xs);
    }

    .console-status-section {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }

    /* Console Icons */
    .console-icons {
        display: flex;
        gap: 6px;
    }

    .icon-btn {
        background: none;
        border: none;
        color: var(--color-primary);
        cursor: pointer;
        padding: 3px;
        border-radius: 4px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .icon-btn:hover {
        background: rgba(var(--color-primary-rgb, 59, 130, 246), 0.1);
        transform: scale(1.1);
    }

    .icon-btn.delete-btn {
        color: var(--color-error);
    }

    .icon-btn.delete-btn:hover {
        background: rgba(var(--color-error-rgb), 0.1);
        color: var(--color-error);
    }

    /* Compact Console Status */
    .console-status {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        box-shadow: var(--shadow-xs);
        transition: all 0.3s ease;
    }

    .status-icon {
        font-size: 14px;
        animation: pulse-icon 2s infinite;
    }

    .console-status.status-available {
        background: var(--color-success);
        color: var(--color-white);
    }

    .console-status.status-occupied {
        background: var(--color-warning);
        color: var(--color-white);
    }

    .console-status.status-maintenance {
        background: var(--color-error);
        color: var(--color-white);
    }

    /* Console Information Section */
    .console-info-section {
        padding: 20px 24px;
        background: var(--color-surface);
    }

    .console-specs-card {
        background: var(--color-bg-1);
        border-radius: 8px;
        padding: 12px;
        border: 1px solid var(--color-border);
        display: grid;
        gap: 8px;
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 0;
        border-bottom: 1px solid var(--color-border);
        transition: all 0.2s ease;
    }

    .spec-item:last-child {
        border-bottom: none;
    }

    .spec-item:hover {
        background: var(--color-bg-2);
        margin: 0 -8px;
        padding: 8px 8px;
        border-radius: 8px;
    }

    .spec-icon {
        font-size: 14px;
        width: 20px;
        text-align: center;
    }

    .spec-text {
        color: var(--color-text);
        font-weight: 500;
        font-size: 12px;
        flex: 1;
    }

    /* Console Content Section */
    .console-content-section {
        flex: 1;
        padding: 0 16px 16px 16px;
        display: flex;
        flex-direction: column;
        padding-bottom: 0;
    }

    /* Occupied Session */
    .occupied-session {
        background: var(--color-bg-1);
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 6px;
        flex: 1;
    }

    .session-timer {
        font-size: 28px;
        font-weight: 700;
        color: #fff !important;
        text-align: center;
        margin-bottom: 12px;
        font-family: 'Courier New', monospace;
        background: var(--color-success);
        padding: 8px;
        border-radius: 6px;
    }

    .session-details {
        color: #ffffff !important;
    }

    .session-details p {
        /* margin: 0 0 6px 0; */
        font-size: 13px;
        color: black !important;
        height: 20px;
        font-weight: normal;
    }

    /* Session Items */
    .session-items {
        margin-top: 10px;
    }

    .items-label {
        color: black !important;
        font-size: 13px;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .session-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.5);
        padding: 6px 10px;
        border-radius: 4px;
        margin-bottom: 4px;
        color: #000 !important;
        font-size: 12px;
    }

    .remove-item-btn {
        background: none;
        border: none;
        color: #ed288bff !important;
        cursor: pointer;
        font-size: 14px;
        padding: 2px 6px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .remove-item-btn:hover {
        background: rgba(255, 105, 180, 0.2);
        transform: scale(1.1);
    }

    /* Maintenance Content */
    .maintenance-content {
        background: #374151;
        border-radius: 6px;
        padding: 40px 20px;
        margin-bottom: 16px;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .maintenance-text {
        color: #9CA3AF;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        text-transform: uppercase;
    }

    /* Compact Action Buttons */
    .console-actions {
        margin-top: auto;
        padding: 12px 16px;
    }

    .btn--start {
        width: 100%;
        background: linear-gradient(135deg, var(--color-success) 0%, var(--color-success) 100%) !important;
        color: var(--color-white) !important;
        border: none;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-xs);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn--start::before {
        content: '🎮';
        font-size: 16px;
    }

    .btn--start:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    /* Compact Session Controls */
    .session-controls {
        display: flex;
        flex-direction: column;
        gap: 8px;
        background: var(--color-bg-1);
        padding: 12px;
        border-radius: 8px;
        border: 1px solid var(--color-border);
        margin: 6px 6px;
        width: 100%;
    }

    .top-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }

    .top-buttons .btn {
        background: var(--color-surface) !important;
        color: var(--color-text) !important;
        border: 1px solid var(--color-border) !important;
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
    }

    .top-buttons .btn:hover {
        background: var(--color-primary) !important;
        color: var(--color-white) !important;
        transform: translateY(-1px);
    }

    .top-buttons .btn:nth-child(1)::before {
        content: '🍕';
        font-size: 12px;
    }

    .top-buttons .btn:nth-child(2)::before {
        content: '🔄';
        font-size: 12px;
    }

    .top-buttons .btn:nth-child(3)::before {
        content: '👥';
        font-size: 12px;
    }

    .top-buttons .btn:nth-child(4)::before {
        content: '⏸️';
        font-size: 12px;
    }

    .bottom-buttons {
        display: flex;
        gap: 6px;
        align-items: center;
        margin-top: 6px;
    }

    .bottom-buttons .btn--lg {
        flex: 1;
        background: linear-gradient(135deg, var(--color-error) 0%, #dc2626 100%) !important;
        color: var(--color-white) !important;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .bottom-buttons .btn--lg::before {
        content: '🛑';
        font-size: 14px;
    }

    .bottom-buttons .btn--lg:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    /* Enhanced F&D Modal Styles */
    .fandd-modal {
        max-width: 900px;
        max-height: 85vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        background: var(--color-surface);
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--color-border);
    }

    .fandd-modal h3 {
        background: linear-gradient(135deg, var(--color-success) 0%, var(--color-success) 100%);
        color: var(--color-white);
        margin: 0;
        padding: 16px 20px;
        border-radius: 12px 12px 0 0;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .fandd-modal h3::before {
        content: '🍕';
        font-size: 20px;
        animation: bounce 2s infinite;
    }

    .fandd-modal h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
    }

    .fandd-items-container {
        flex: 1;
        overflow-y: auto;
        margin: 0;
        max-height: 55vh;
        padding: 16px 20px;
        background: var(--color-background);
    }

    .fandd-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
        padding: 0;
    }

    .fandd-item-card {
        border: 2px solid var(--color-border);
        border-radius: 16px;
        padding: 0;
        background: var(--color-surface);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        cursor: pointer;
    }

    .fandd-item-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 16px 16px 0 0;
    }

    .fandd-item-card::after {
        content: '🍽️';
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 24px;
        opacity: 0.1;
        transition: all 0.3s ease;
    }

    .fandd-item-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: var(--shadow-lg);
        border-color: var(--color-primary);
    }

    .fandd-item-card:hover::after {
        opacity: 0.3;
        transform: rotate(15deg) scale(1.2);
    }

    .fandd-item-card.selected {
        border-color: var(--color-success);
        background: linear-gradient(135deg, var(--color-surface) 0%, rgba(var(--color-success-rgb), 0.05) 100%);
        transform: scale(1.02);
        box-shadow: var(--shadow-md), 0 0 20px rgba(var(--color-success-rgb), 0.2);
    }

    .fandd-item-header {
        padding: 12px 16px 10px 16px;
        border-bottom: 1px solid var(--color-border);
        background: linear-gradient(135deg, var(--color-bg-1) 0%, var(--color-bg-2) 100%);
        position: relative;
        z-index: 2;
    }

    .fandd-item-name {
        font-weight: 700;
        font-size: 14px;
        color: var(--color-text);
        margin: 0 0 6px 0;
        line-height: 1.2;
        letter-spacing: -0.01em;
    }

    .fandd-item-category {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        background: var(--color-primary);
        color: var(--color-white);
        border-radius: 16px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .fandd-item-body {
        padding: 16px;
    }

    .fandd-item-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-primary);
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 6px;
        background: var(--color-bg-1);
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid var(--color-border);
    }

    .fandd-item-price::before {
        content: '💰';
        font-size: 14px;
    }

    .fandd-item-stock {
        color: var(--color-text-secondary);
        font-size: 14px;
        margin: 0 0 20px 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: var(--color-bg-2);
        border-radius: 8px;
        border: 1px solid var(--color-border);
    }

    .fandd-item-stock::before {
        content: '📦';
        font-size: 16px;
    }

    .fandd-item-stock.low-stock {
        color: var(--color-error);
        background: rgba(var(--color-error-rgb), 0.1);
        border-color: var(--color-error);
    }

    .fandd-item-stock.low-stock::before {
        content: '⚠️';
    }

    .fandd-quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: var(--color-bg-1);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
    }

    .fandd-qty-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        background: var(--color-primary);
        color: var(--color-white);
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-xs);
    }

    .fandd-qty-btn:hover {
        background: var(--color-primary-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .fandd-qty-btn:active {
        transform: translateY(0);
    }

    .fandd-qty-btn:disabled {
        background: var(--color-secondary);
        color: var(--color-text-secondary);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .fandd-qty-input {
        width: 50px;
        height: 32px;
        text-align: center;
        border: 1px solid var(--color-primary);
        border-radius: 8px;
        background: var(--color-surface);
        font-weight: 600;
        font-size: 14px;
        color: var(--color-text);
        transition: all 0.2s ease;
    }

    .fandd-qty-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: var(--focus-ring);
    }

    .fandd-modal-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 24px 32px;
        border-top: 1px solid var(--color-border);
        background: var(--color-bg-1);
        border-radius: 0 0 20px 20px;
    }

    .fandd-selected-summary {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        color: var(--color-text);
        background: var(--color-surface);
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
    }

    .fandd-selected-summary::before {
        content: '🛒';
        font-size: 18px;
    }

    .fandd-action-buttons {
        display: flex;
        gap: 12px;
    }

    .fandd-modal-actions .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .fandd-modal-actions .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .fandd-modal-actions .btn:hover::before {
        left: 100%;
    }

    .fandd-modal-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Enhanced Scrollbar styling for F&D modal */
    .fandd-items-container::-webkit-scrollbar {
        width: 12px;
    }

    .fandd-items-container::-webkit-scrollbar-track {
        background: var(--color-bg-1);
        border-radius: 8px;
        margin: 8px 0;
    }

    .fandd-items-container::-webkit-scrollbar-thumb {
        background: grey;
        border-radius: 8px;
        border: 1px solid var(--color-bg-1);
        transition: all 0.2s ease;
    }

    .fandd-items-container::-webkit-scrollbar-thumb:hover {
        background: grey;
        transform: scale(1.1);
    }

    /* F&D Modal Animations */
    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }

    @keyframes slideInFromBottom {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .fandd-item-card {
        animation: slideInFromBottom 0.4s ease-out;
    }

    .fandd-item-card:nth-child(even) {
        animation-delay: 0.1s;
    }

    .fandd-item-card:nth-child(3n) {
        animation-delay: 0.2s;
    }

    .fandd-modal {
        animation: fadeInScale 0.3s ease-out;
    }

    /* Category-specific item styling */
    .fandd-item-card[data-category="beverages"] .fandd-item-category {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .fandd-item-card[data-category="beverages"]::after {
        content: '🥤';
    }

    .fandd-item-card[data-category="snacks"] .fandd-item-category {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .fandd-item-card[data-category="snacks"]::after {
        content: '🍿';
    }

    .fandd-item-card[data-category="meals"] .fandd-item-category {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .fandd-item-card[data-category="meals"]::after {
        content: '🍽️';
    }

    .fandd-item-card[data-category="desserts"] .fandd-item-category {
        background: linear-gradient(135deg, #ec4899, #db2777);
    }

    .fandd-item-card[data-category="desserts"]::after {
        content: '🍰';
    }

    .fandd-item-card[data-category="other"] .fandd-item-category {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .fandd-item-card[data-category="other"]::after {
        content: '🎁';
    }

    /* Loading state for F&D items */
    .fandd-item-card.loading {
        background: linear-gradient(90deg, var(--color-surface) 25%, var(--color-bg-1) 50%, var(--color-surface) 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }

        100% {
            background-position: calc(200px + 100%) 0;
        }
    }

    /* Responsive F&D Modal */
    @media (max-width: 768px) {
        .fandd-modal {
            max-width: 95vw;
            margin: 20px;
        }

        .fandd-items-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .fandd-modal h3 {
            padding: 20px 24px;
            font-size: 20px;
        }

        .fandd-items-container {
            padding: 20px 24px;
        }

        .fandd-modal-actions {
            flex-direction: column;
            gap: 12px;
            padding: 20px 24px;
        }

        .fandd-action-buttons {
            width: 100%;
        }

        .fandd-modal-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Billing Modal Styles */
    .billing-modal {
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .billing-content {
        padding: 20px 0;
        font-family: 'Courier New', monospace;
    }

    .customer-info {
        margin-bottom: 20px;
        padding: 15px;
        background: var(--color-bg-1);
        border-radius: 8px;
    }

    .customer-info p {
        margin: 0 0 8px 0;
        color: var(--color-text);
    }

    .gaming-segments,
    .food-drinks-section,
    .discounts-section {
        margin-bottom: 20px;
        padding: 15px;
        background: var(--color-bg-1);
        border-radius: 8px;
    }

    .gaming-segments h4,
    .food-drinks-section h4,
    .discounts-section h4 {
        margin: 0 0 15px 0;
        color: var(--color-text);
        font-size: 16px;
    }

    .segment-item,
    .food-drink-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-secondary);
    }

    .segment-item:last-child,
    .food-drink-item:last-child {
        border-bottom: none;
    }

    .pause-history {
        margin: 15px 0;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .gaming-total,
    .food-drinks-total {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid var(--color-border);
        color: var(--color-text);
        font-size: 16px;
    }

    .grand-total {
        margin: 20px 0;
        padding: 20px;
        background: var(--color-primary);
        color: var(--color-btn-primary-text);
        border-radius: 8px;
        text-align: center;
        font-size: 18px;
    }

    .billing-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding: 20px 0 0 0;
        border-top: 1px solid var(--color-border);
    }

    /* Bill Header */
    .bill-header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        padding: 20px;
        background: var(--color-bg-1);
        border-radius: 8px;
        text-align: center;
    }

    .bill-logo {
        margin-right: 20px;
    }

    .bill-logo .logo-img {
        max-width: 100px;
        height: auto;
        border-radius: 8px;
    }

    .bill-title h2 {
        margin: 0 0 5px 0;
        color: var(--color-text);
        font-size: 24px;
        font-family: 'Courier New', monospace;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .bill-title p {
        margin: 0;
        color: var(--color-text-secondary);
        font-size: 14px;
        font-family: 'Courier New', monospace;
    }

    /* Coupon Section */
    .coupon-section {
        margin-bottom: 15px;
    }

    .coupon-section label {
        display: block;
        margin-bottom: 8px;
        color: var(--color-text);
        font-weight: 600;
    }

    .coupon-input-group {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .coupon-input-group select {
        flex: 1;
    }

    .coupon-input-group .btn {
        white-space: nowrap;
    }

    /* Payment Section */
    .payment-section {
        margin-bottom: 20px;
        padding: 15px;
        background: var(--color-bg-2);
        border-radius: 8px;
    }

    .payment-section h4 {
        margin: 0 0 15px 0;
        color: var(--color-text);
        font-size: 16px;
    }

    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .payment-option {
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 10px;
        border-radius: 6px;
        transition: background-color 0.2s ease;
    }

    .payment-option:hover {
        background: var(--color-bg-1);
    }

    .payment-option input[type="radio"] {
        margin-right: 10px;
        transform: scale(1.2);
    }

    .payment-option span {
        color: var(--color-text);
        font-weight: 500;
    }

    .split-inputs {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .split-inputs .form-control {
        flex: 1;
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<style>
    /* Lo
ading and Error States */
    .loading-message,
    .error-message {
        text-align: center;
        padding: 40px 20px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 12px;
        margin: 20px;
    }

    .loading-message {
        background: linear-gradient(135deg, var(--color-bg-1) 0%, var(--color-bg-2) 100%);
        color: var(--color-text);
        border: 1px solid var(--color-border);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .loading-message::before {
        content: '🍕';
        font-size: 24px;
        animation: bounce 1s infinite;
    }

    .error-message {
        background: rgba(var(--color-error-rgb), 0.1);
        color: var(--color-error);
        border: 1px solid var(--color-error);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .error-message::before {
        content: '❌';
        font-size: 24px;
    }

    /* Enhanced Modal Backdrop */
    .modal {
        backdrop-filter: blur(8px);
        background: rgba(0, 0, 0, 0.6);
    }

    /* Pulse animation for selected items */
    .fandd-item-card.selected {
        animation: selectedPulse 2s infinite;
    }

    @keyframes selectedPulse {

        0%,
        100% {
            box-shadow: var(--shadow-md), 0 0 20px rgba(var(--color-success-rgb), 0.2);
        }

        50% {
            box-shadow: var(--shadow-lg), 0 0 30px rgba(var(--color-success-rgb), 0.4);
        }
    }

    /* Quantity input animations */
    .fandd-qty-input {
        transition: all 0.3s ease;
    }

    .fandd-qty-input:focus {
        transform: scale(1.05);
    }

    /* Button press animation */
    .fandd-qty-btn:active {
        transform: translateY(0) scale(0.95);
    }

    /* Enhanced tooltip styles */
    .fandd-qty-btn[title]:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: var(--color-text);
        color: var(--color-surface);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
        margin-bottom: 4px;
    }

    /* Success feedback animation */
    @keyframes successFeedback {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .fandd-item-card.success-feedback {
        animation: successFeedback 0.3s ease;
    }

    /* Enhanced Console Card Animations */
    @keyframes pulse-occupied {

        0%,
        100% {
            box-shadow: var(--shadow-sm), 0 0 0 0 rgba(var(--color-warning-rgb), 0.4);
        }

        50% {
            box-shadow: var(--shadow-md), 0 0 0 12px rgba(var(--color-warning-rgb), 0);
        }
    }

    @keyframes gradient-shift {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    @keyframes pulse-icon {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }

    @keyframes blink {

        0%,
        50% {
            opacity: 1;
        }

        51%,
        100% {
            opacity: 0.4;
        }
    }

    /* Card loading animation */
    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-30px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    .console-card {
        animation: slideInFromLeft 0.5s ease-out;
    }

    .console-card:nth-child(even) {
        animation-delay: 0.1s;
    }

    .console-card:nth-child(3n) {
        animation-delay: 0.2s;
    }

    /* Maintenance Content Enhancement */
    .maintenance-content {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
        background: linear-gradient(135deg, rgba(var(--color-error-rgb), 0.1) 0%, rgba(var(--color-error-rgb), 0.05) 100%);
        border-radius: 12px;
        border: 2px dashed var(--color-error);
        margin: 16px 0;
    }

    .maintenance-text {
        font-size: 24px;
        font-weight: 800;
        color: var(--color-error);
        text-transform: uppercase;
        letter-spacing: 2px;
        display: flex;
        align-items: center;
        gap: 12px;
        /* animation: blink 2s infinite; */
    }

    .maintenance-text::before {
        content: '🔧';
        font-size: 28px;
    }

    /* Console Type Specific Icons */
    .console-card[data-type="pc"] .console-icon {
        /* background: linear-gradient(135deg, #3b82f6, #1d4ed8); */
        color: white;
    }

    .console-card[data-type="ps5"] .console-icon,
    .console-card[data-type="playstation 5"] .console-icon {
        /* background: linear-gradient(135deg, #1e40af, #1e3a8a); */
        color: white;
    }

    .console-card[data-type="xbox"] .console-icon,
    .console-card[data-type="xbox series x/s"] .console-icon {
        /* background: linear-gradient(135deg, #16a34a, #15803d); */
        color: white;
    }

    .console-card[data-type="nintendo switch"] .console-icon {
        /* background: linear-gradient(135deg, #dc2626, #b91c1c); */
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .console-grid {
            grid-template-columns: 1fr;
            gap: 16px;
            margin-top: 16px;
        }

        .console-card {
            min-height: 360px;
        }

        .console-card-header {
            padding: 20px 20px 16px 20px;
        }

        .console-title-section {
            gap: 12px;
        }

        .console-icon {
            font-size: 24px;
            padding: 8px;
        }

        .console-name {
            font-size: 18px;
        }

        .console-info-section {
            padding: 16px 20px;
        }

        .console-content-section {
            padding: 0 20px 16px 20px;
        }

        .spec-item {
            padding: 6px 0;
        }

        .spec-text {
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .console-card-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }

        .console-status-section {
            align-items: flex-start;
            flex-direction: row;
            justify-content: space-between;
            width: 100%;
        }

        .console-icons {
            order: 2;
        }
    }
</style>