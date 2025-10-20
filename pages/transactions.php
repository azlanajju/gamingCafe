<?php
$currentPage = 'transactions';

// Check if user is admin - redirect staff to dashboard
require_once __DIR__ . '/../config/auth.php';
if (!Auth::hasRole('Admin')) {
    header('Location: ' . SITE_URL . '/pages/dashboard.php');
    exit();
}

require_once __DIR__ . '/../includes/header.php';
?>

<section id="transactions" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Transaction History</h2>
        <span id="total-count">0 Transactions</span>
    </div>

    <div class="card">
        <!-- Filters and Search Toolbar -->
        <div class="transaction-toolbar">
            <div class="toolbar-left">
                <div class="search-box">
                    <input type="text" id="search-input" class="search-input" placeholder=" Search customer, console...">
                    <button class="clear-btn" id="clear-search" style="display: none;"> </button>
                </div>
            </div>
            <div class="toolbar-right">
                <select id="console-filter" class="filter-select">
                    <option value="">All Consoles</option>
                </select>
                <select id="payment-filter" class="filter-select">
                    <option value="">All Payments</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="upi">UPI</option>
                    <option value="cash_upi">Cash + UPI</option>
                    <option value="online">Online</option>
                </select>
                <input type="date" id="start-date" class="date-input" title="Start Date">
                <input type="date" id="end-date" class="date-input" title="End Date">
                <button class="btn btn-primary" id="apply-filters">Apply Filters</button>
                <button class="btn btn-secondary" id="reset-filters">Reset</button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>SL.No</th>
                        <th>Customer</th>
                        <th>Console</th>
                        <th>Duration</th>
                        <th>Gaming Amount</th>
                        <th>Food Amount</th>
                        <th>Discount</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="transactions-table-body">
                    <!-- Transactions will be loaded here dynamically -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><strong>TOTAL:</strong></td>
                        <td id="gaming-total">₹0.00</td>
                        <td id="food-total">₹0.00</td>
                        <td id="discount-total">₹0.00</td>
                        <td id="grand-total">₹0.00</td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label for="items-per-page" style="color: var(--color-text);">Items per page:</label>
                    <select id="items-per-page">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <div class="pagination-info">
                    Showing <span id="showing-start">0</span> to <span id="showing-end">0</span> of <span id="total-items">0</span> transactions
                </div>
                <div style="display: flex; gap: 5px;">
                    <button class="pagination-btn" id="first-page">First</button>
                    <button class="pagination-btn" id="prev-page">Previous</button>
                    <button class="pagination-btn" id="next-page">Next</button>
                    <button class="pagination-btn" id="last-page">Last</button>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Page scroll container */
    #transactions {
        height: calc(100vh - 80px);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* Beautiful Enhanced Table Styles */
    .table-container {
        flex: 1;
        overflow: auto;
        border-radius: 0;
        border: none;
        margin: 0;
        box-shadow: none;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        min-width: 1400px;
        background: var(--color-surface);
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--color-border);
    }

    .data-table thead {
        background: var(--color-bg-1);
        color: var(--color-text);
        position: relative;
    }

    .data-table thead::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--color-bg-1);
        z-index: -1;
    }

    .data-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: none;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
        color: var(--color-text);
        height: 48px;
    }

    /* Custom column widths */
    /* SL.No */
    .data-table th:nth-child(1),
    .data-table td:nth-child(1) {
        width: 60px;
        min-width: 60px;
    }

    /* Customer */
    .data-table th:nth-child(2),
    .data-table td:nth-child(2) {
        width: 120px;
        min-width: 120px;
    }

    /* Console */
    .data-table th:nth-child(3),
    .data-table td:nth-child(3) {
        width: 140px;
        min-width: 140px;
    }

    /* Duration */
    .data-table th:nth-child(4),
    .data-table td:nth-child(4) {
        width: 100px;
        min-width: 100px;
    }

    /* Gaming Amount */
    .data-table th:nth-child(5),
    .data-table td:nth-child(5) {
        width: 120px;
        min-width: 120px;
    }

    /* Food Amount */
    .data-table th:nth-child(6),
    .data-table td:nth-child(6) {
        width: 120px;
        min-width: 120px;
    }

    /* Total Amount */
    .data-table th:nth-child(7),
    .data-table td:nth-child(7) {
        width: 120px;
        min-width: 120px;
    }

    /* Payment */
    .data-table th:nth-child(8),
    .data-table td:nth-child(8) {
        width: 100px;
        min-width: 100px;
    }

    /* Date */
    .data-table th:nth-child(9),
    .data-table td:nth-child(9) {
        width: 120px;
        min-width: 120px;
    }

    /* User */
    .data-table th:nth-child(10),
    .data-table td:nth-child(10) {
        width: 120px;
        min-width: 120px;
    }

    /* Actions */
    .data-table th:nth-child(11),
    .data-table td:nth-child(11) {
        width: 100px;
        min-width: 100px;
    }

    /* Delete */

    .data-table td {
        padding: 8px 12px;
        border-bottom: 1px solid var(--color-border);
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        vertical-align: middle;
        color: var(--color-text);
        font-size: 13px;
        transition: all 0.3s ease;
        height: 48px;
    }

    .data-table tbody tr {
        background: var(--color-surface);
        transition: all 0.3s ease;
    }

    .data-table tbody tr:hover {
        background: var(--color-bg-1);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .data-table tbody tr:nth-child(even) {
        background: var(--color-bg-1);
    }

    .data-table tbody tr:nth-child(even):hover {
        background: var(--color-bg-2);
    }

    .data-table tfoot {
        background: var(--color-bg-1);
        color: var(--color-text);
        font-weight: bold;
        position: relative;
    }

    .data-table tfoot::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--color-bg-1);
        z-index: -1;
    }

    .data-table tfoot td {
        border-bottom: none;
        padding: 16px 12px;
        font-size: 14px;
    }

    /* Compact Action Buttons */
    .action-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: nowrap;
        justify-content: flex-start;
        align-items: center;
    }

    .payment-breakdown {
        margin-top: 4px;
        padding: 4px 8px;
        background: var(--color-bg-1);
        border-radius: 4px;
        border: 1px solid var(--color-border);
    }

    .payment-breakdown small {
        color: var(--color-text-secondary);
        font-size: 11px;
        font-weight: 500;
    }

    .btn {
        padding: 4px 6px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 10px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-xs);
        min-width: 28px;
        height: 24px;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(var(--color-text-rgb, 17, 24, 39), 0.1), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-sm {
        padding: 3px 5px;
        font-size: 9px;
        min-width: 24px;
        height: 20px;
    }

    .btn-primary {
        background: var(--color-success);
        color: var(--color-white);
        border: 1px solid var(--color-success);
    }

    .btn-primary:hover {
        background: var(--color-success);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-warning {
        background: var(--color-warning);
        color: var(--color-white);
        border: 1px solid var(--color-warning);
    }

    .btn-warning:hover {
        background: var(--color-warning);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-info {
        background: var(--color-info);
        color: var(--color-white);
        border: 1px solid var(--color-info);
    }

    .btn-info:hover {
        background: var(--color-info);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-danger {
        background: var(--color-error);
        color: var(--color-white);
        border: 1px solid var(--color-error);
    }

    .btn-danger:hover {
        background: var(--color-error);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: var(--color-secondary);
        color: var(--color-text);
        border: 1px solid var(--color-border);
        font-size: 9px;
        padding: 4px 8px;
        min-width: 35px;
        height: 22px;
    }

    .btn-secondary:hover {
        background: var(--color-secondary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow-xs);
    }

    /* Beautiful Transaction Toolbar */
    .transaction-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: var(--color-surface);
        border-bottom: 1px solid var(--color-border);
        flex-shrink: 0;
        z-index: 100;
    }

    .toolbar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .toolbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        padding: 12px 40px 12px 16px;
        border: 2px solid var(--color-border);
        border-radius: 12px;
        font-size: 14px;
        width: 280px;
        background: var(--color-surface);
        color: var(--color-text);
        transition: all 0.3s ease;
    }

    .search-input::placeholder {
        color: var(--color-text-secondary);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: var(--focus-ring);
        background: var(--color-surface);
    }

    .clear-btn {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        font-size: 18px;
        color: var(--color-text-secondary);
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .clear-btn:hover {
        color: var(--color-error);
        background: rgba(var(--color-error-rgb), 0.1);
        transform: scale(1.1);
    }

    /* Enhanced stats display */
    .transaction-toolbar span {
        padding: 8px 15px !important;
        background: var(--color-bg-1) !important;
        border: 1px solid var(--color-border) !important;
        color: var(--color-text) !important;
        border-radius: 8px !important;
    }

    /* Enhanced select styling */
    #items-per-page {
        padding: 8px 12px;
        border: 2px solid var(--color-border);
        border-radius: 8px;
        background: var(--color-surface);
        color: var(--color-text);
        font-size: 14px;
        transition: all 0.3s ease;
    }

    #items-per-page:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: var(--focus-ring);
    }

    /* Pagination Container */
    .pagination-container {
        background: var(--color-surface);
        border-top: 1px solid var(--color-border);
        flex-shrink: 0;
        z-index: 100;
    }

    .pagination-btn {
        padding: 8px 14px;
        margin: 0 3px;
        border: 2px solid var(--color-border);
        border-radius: 8px;
        background: var(--color-surface);
        color: var(--color-text);
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .pagination-btn:hover:not(:disabled) {
        background: var(--color-bg-1);
        border-color: var(--color-primary);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .pagination-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: var(--color-bg-1);
    }

    /* Pagination info styling */
    .pagination-info {
        color: var(--color-text);
        font-weight: 500;
    }

    /* Filter select styling */
    .filter-select {
        padding: 8px 12px;
        border: 2px solid var(--color-border);
        border-radius: 8px;
        background: var(--color-surface);
        color: var(--color-text);
        font-size: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: var(--focus-ring);
    }

    .filter-select option {
        background: var(--color-surface);
        color: var(--color-text);
    }

    /* Date input styling */
    .date-input {
        padding: 8px 12px;
        border: 2px solid var(--color-border);
        border-radius: 8px;
        background: var(--color-surface);
        color: var(--color-text);
        font-size: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .date-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: var(--focus-ring);
    }

    /* Color scheme for date picker */
    .date-input::-webkit-calendar-picker-indicator {
        cursor: pointer;
    }

    /* Enhanced section styling */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding: 20px 0;
        flex-shrink: 0;
    }

    .section-title {
        color: var(--color-text);
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }

    .section-header span {
        padding: 12px 20px;
        background: var(--color-bg-1);
        border: 1px solid var(--color-border);
        border-radius: 8px;
        font-weight: 600;
        color: var(--color-text);
    }

    /* Card styling */
    .card {
        background: var(--color-surface);
        border-radius: 0;
        box-shadow: none;
        border: none;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .transaction-toolbar {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .toolbar-left,
        .toolbar-right {
            justify-content: center;
        }

        .search-input {
            width: 100%;
        }

        .table-container {
            margin: 10px 0;
        }

        .data-table {
            font-size: 12px;
        }

        .data-table th,
        .data-table td {
            padding: 8px 6px;
        }
    }

    /* Print Styles */
    @media print {

        .transaction-toolbar,
        .pagination-container {
            display: none !important;
        }

        .table-container {
            overflow: visible !important;
            border: none !important;
        }

        .data-table {
            min-width: auto !important;
            font-size: 12px !important;
        }

        .data-table th,
        .data-table td {
            padding: 6px 4px !important;
            border: 1px solid #000 !important;
        }

        .action-buttons {
            display: none !important;
        }
    }
</style>

<script>
    let allTransactions = [];
    let filteredTransactions = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    let uniqueConsoles = new Set();

    // Initialize date inputs - leave empty to show all transactions
    function initializeDates() {
        // Clear date filters to show all transactions by default
        document.getElementById('start-date').value = '';
        document.getElementById('end-date').value = '';
    }

    // Load transactions with filters
    function loadTransactions() {
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;

        let url = `${SITE_URL}/api/transactions.php?action=list`;
        if (startDate) url += `&start_date=${startDate}`;
        if (endDate) url += `&end_date=${endDate}`;

        fetch(url)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    allTransactions = result.data;
                    populateConsoleFilter();
                    applyFilters();
                }
            })
            .catch(err => console.error('Error loading transactions:', err));
    }

    // Populate console filter dropdown
    function populateConsoleFilter() {
        uniqueConsoles.clear();
        allTransactions.forEach(txn => {
            if (txn.console_name) {
                uniqueConsoles.add(txn.console_name);
            }
        });

        const consoleFilter = document.getElementById('console-filter');
        consoleFilter.innerHTML = '<option value="">All Consoles</option>';
        Array.from(uniqueConsoles).sort().forEach(console => {
            const option = document.createElement('option');
            option.value = console;
            option.textContent = console;
            consoleFilter.appendChild(option);
        });
    }

    // Apply all filters
    function applyFilters() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const consoleFilter = document.getElementById('console-filter').value.toLowerCase();
        const paymentFilter = document.getElementById('payment-filter').value.toLowerCase();

        filteredTransactions = allTransactions.filter(txn => {
            // Search filter
            const matchesSearch = !searchTerm ||
                (txn.customer_name && txn.customer_name.toLowerCase().includes(searchTerm)) ||
                (txn.console_name && txn.console_name.toLowerCase().includes(searchTerm)) ||
                (txn.user_name && txn.user_name.toLowerCase().includes(searchTerm));

            // Console filter
            const matchesConsole = !consoleFilter ||
                (txn.console_name && txn.console_name.toLowerCase() === consoleFilter);

            // Payment filter
            const matchesPayment = !paymentFilter ||
                (txn.payment_method && txn.payment_method.toLowerCase() === paymentFilter);

            return matchesSearch && matchesConsole && matchesPayment;
        });

        currentPage = 1;
        displayTransactions();
    }

    // Display transactions with pagination
    // Helper function to format duration
    function formatDuration(minutes) {
        if (!minutes || minutes === 0) return '0:00';
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours}:${mins.toString().padStart(2, '0')}:00`;
    }

    // Helper function to format date
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-CA'); // YYYY-MM-DD format
    }

    function displayTransactions() {
        const tbody = document.getElementById('transactions-table-body');
        if (!tbody) {
            console.error('Table body element not found');
            return;
        }

        tbody.innerHTML = '';

        let gamingTotal = 0;
        let foodTotal = 0;
        let grandTotal = 0;

        if (filteredTransactions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="12" style="text-align: center; padding: 20px; color: #999;">No transactions found</td></tr>';
            const gamingTotalEl = document.getElementById('gaming-total');
            const foodTotalEl = document.getElementById('food-total');
            const discountTotalEl = document.getElementById('discount-total');
            const grandTotalEl = document.getElementById('grand-total');

            if (gamingTotalEl) gamingTotalEl.textContent = '₹0.00';
            if (foodTotalEl) foodTotalEl.textContent = '₹0.00';
            if (discountTotalEl) discountTotalEl.textContent = '₹0.00';
            if (grandTotalEl) grandTotalEl.textContent = '₹0.00';

            updatePaginationInfo(0, 0);

            // Set dynamic heights for empty state
            setDynamicHeights(0);
            return;
        }

        // Display transactions with pagination
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = itemsPerPage === 'all' ? filteredTransactions.length : Math.min(startIndex + itemsPerPage, filteredTransactions.length);

        // Show only transactions for current page
        const transactionsToShow = filteredTransactions.slice(startIndex, endIndex);

        transactionsToShow.forEach((txn, index) => {
            const tr = document.createElement('tr');
            const globalIndex = startIndex + index + 1;

            tr.innerHTML = `
            <td>${globalIndex}</td>
            <td>${txn.customer_name}</td>
            <td>${txn.console_name || '-'}</td>
            <td>${formatDuration(txn.total_duration_minutes || txn.duration || 0)}</td>
            <td>₹${parseFloat(txn.gaming_amount || 0).toFixed(2)}</td>
            <td>₹${parseFloat(txn.fandd_amount || txn.food_amount || 0).toFixed(2)}</td>
            <td>₹${parseFloat(txn.discount_amount || 0).toFixed(2)}</td>
            <td>₹${parseFloat(txn.total_amount || 0).toFixed(2)}</td>
            <td>
                ${txn.payment_method || txn.payment_status || 'pending'}
                ${txn.payment_breakdown ? `
                    <div class="payment-breakdown">
                        <small>Cash: ${txn.payment_breakdown.cash_amount}</small><br>
                        <small>UPI: ${txn.payment_breakdown.upi_amount}</small>
                    </div>
                ` : ''}
            </td>
            <td>${formatDate(txn.created_at || txn.transaction_date)}</td>
            <td>${txn.user_name || '-'}</td>
            <td class="action-buttons">
                <button class="btn btn-sm btn-secondary" onclick="viewTransaction(${txn.id})" title="View Details">
                    View
                </button>
                <button class="btn btn-sm btn-secondary" onclick="printTransaction(${txn.id})" title="Print Receipt">
                    Print
                </button>
            </td>
        `;
            tbody.appendChild(tr);
        });

        // Calculate totals from displayed transactions only
        let discountTotal = 0;
        transactionsToShow.forEach(txn => {
            gamingTotal += parseFloat(txn.gaming_amount || 0);
            foodTotal += parseFloat(txn.fandd_amount || txn.food_amount || 0);
            discountTotal += parseFloat(txn.discount_amount || 0);
            grandTotal += parseFloat(txn.total_amount || 0);
        });

        const gamingTotalEl = document.getElementById('gaming-total');
        const foodTotalEl = document.getElementById('food-total');
        const discountTotalEl = document.getElementById('discount-total');
        const grandTotalEl = document.getElementById('grand-total');

        if (gamingTotalEl) gamingTotalEl.textContent = '₹' + gamingTotal.toFixed(2);
        if (foodTotalEl) foodTotalEl.textContent = '₹' + foodTotal.toFixed(2);
        if (discountTotalEl) discountTotalEl.textContent = '₹' + discountTotal.toFixed(2);
        if (grandTotalEl) grandTotalEl.textContent = '₹' + grandTotal.toFixed(2);

        // Update total count and pagination
        const totalCountEl = document.getElementById('total-count');
        const totalItemsEl = document.getElementById('total-items');

        if (totalCountEl) totalCountEl.textContent = `${filteredTransactions.length} Transaction${filteredTransactions.length !== 1 ? 's' : ''}`;
        if (totalItemsEl) totalItemsEl.textContent = filteredTransactions.length;

        const totalPages = Math.ceil(filteredTransactions.length / itemsPerPage);
        const showingStart = transactionsToShow.length > 0 ? startIndex + 1 : 0;
        const showingEnd = endIndex;

        updatePaginationInfo(showingStart, showingEnd);
        updatePaginationButtons(totalPages);

        // Set dynamic heights based on displayed transaction count
        setDynamicHeights(transactionsToShow.length);
    }

    // Set dynamic heights based on transaction count
    function setDynamicHeights(transactionCount) {
        const rowHeight = 70; // Height of each table row in pixels
        const headerHeight = 48; // Header height
        const footerHeight = 60; // Footer height
        const toolbarHeight = 70; // Toolbar height
        const sectionHeaderHeight = 100; // Section header height
        const paginationHeight = 80; // Pagination height

        // Calculate table body height
        const tableBodyHeight = Math.max(transactionCount * rowHeight, 200); // Minimum 200px

        // Calculate total content height
        const totalContentHeight = sectionHeaderHeight + toolbarHeight + headerHeight + tableBodyHeight + footerHeight + paginationHeight;

        // Set heights
        const tableContainer = document.querySelector('.table-container');
        const contentSection = document.querySelector('.content-section');

        if (tableContainer) {
            tableContainer.style.minHeight = `${tableBodyHeight}px`;
        }

        if (contentSection) {
            contentSection.style.minHeight = `${totalContentHeight}px`;
        }
    }

    // Update pagination info
    function updatePaginationInfo(start, end) {
        const showingStartEl = document.getElementById('showing-start');
        const showingEndEl = document.getElementById('showing-end');

        if (showingStartEl) showingStartEl.textContent = start;
        if (showingEndEl) showingEndEl.textContent = end;
    }

    // Update pagination buttons
    function updatePaginationButtons(totalPages) {
        const firstPageBtn = document.getElementById('first-page');
        const prevPageBtn = document.getElementById('prev-page');
        const nextPageBtn = document.getElementById('next-page');
        const lastPageBtn = document.getElementById('last-page');

        if (firstPageBtn) firstPageBtn.disabled = currentPage === 1;
        if (prevPageBtn) prevPageBtn.disabled = currentPage === 1;
        if (nextPageBtn) nextPageBtn.disabled = currentPage === totalPages || itemsPerPage === 'all';
        if (lastPageBtn) lastPageBtn.disabled = currentPage === totalPages || itemsPerPage === 'all';
    }

    // Action button functions
    function printTransaction(transactionId) {
        // Find the transaction data
        const transaction = allTransactions.find(t => t.id === transactionId);
        if (!transaction) {
            alert('Transaction not found');
            return;
        }

        // Create print window with matching receipt style
        const printWindow = window.open('', '_blank');
        const date = new Date(transaction.created_at || transaction.transaction_date);
        const formattedDate = date.toLocaleDateString('en-IN');
        const formattedTime = date.toLocaleTimeString('en-IN');

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>GameBot Gaming Cafe - Transaction Receipt</title>
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
                <div class="receipt">
                    <div class="receipt-header">
                        <img src="../assets/logo.png" alt="GameBot Gaming Cafe Logo" class="receipt-logo">
                        <h1 class="receipt-title">GameBot Gaming Cafe</h1>
                        <div class="receipt-divider"></div>
                    </div>
                    
                    <div class="receipt-details">
                        <div class="receipt-line"><span>Transaction ID:</span> ${transaction.id}</div>
                        <div class="receipt-line"><span>Customer:</span> ${transaction.customer_name}</div>
                        <div class="receipt-line"><span>Console:</span> ${transaction.console_name || 'N/A'}</div>
                        <div class="receipt-line"><span>Duration:</span> ${transaction.total_duration_minutes || transaction.duration || 0} minutes</div>
                        <div class="receipt-line"><span>Date:</span> ${formattedDate}</div>
                        <div class="receipt-line"><span>Time:</span> ${formattedTime}</div>
                    </div>

                    <div class="receipt-section">
                        <h3 class="section-title">Transaction Summary:</h3>
                        <div class="receipt-summary">
                            <div class="summary-line">
                                <span>Gaming Amount:</span> ₹${parseFloat(transaction.gaming_amount || 0).toFixed(2)}
                            </div>
                            <div class="summary-line">
                                <span>Food & Drinks:</span> ₹${parseFloat(transaction.fandd_amount || transaction.food_amount || 0).toFixed(2)}
                            </div>
                            <div class="summary-line">
                                <span>Payment Method:</span> ${(transaction.payment_method || transaction.payment_status || 'pending').charAt(0).toUpperCase() + (transaction.payment_method || transaction.payment_status || 'pending').slice(1)}
                            </div>
                            ${transaction.payment_breakdown ? `
                                <div class="summary-line">
                                    <span>Cash Amount:</span> ${transaction.payment_breakdown.cash_amount}
                                </div>
                                <div class="summary-line">
                                    <span>UPI Amount:</span> ${transaction.payment_breakdown.upi_amount}
                                </div>
                            ` : ''}
                            <div class="summary-line">
                                <span>Staff:</span> ${transaction.user_name || 'N/A'}
                            </div>
                        </div>
                    </div>

                    <div class="receipt-total">
                        <h2 class="grand-total">Grand Total: ₹${parseFloat(transaction.total_amount || 0).toFixed(2)}</h2>
                    </div>

                    <div class="receipt-footer">
                        <p>Thank you for choosing GameBot Gaming Cafe!</p>
                    </div>
                </div>
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.print();
    }

    function editTransaction(transactionId) {
        // Find the transaction data
        const transaction = allTransactions.find(t => t.id === transactionId);
        if (!transaction) {
            alert('Transaction not found');
            return;
        }

        const currentTotal = parseFloat(transaction.total_amount || 0);
        const adjustment = prompt(
            `Edit Transaction Amount\n\n` +
            `Current Total: ₹${currentTotal.toFixed(2)}\n\n` +
            `Enter adjustment amount:\n` +
            `- Use + for increase (e.g., +50)\n` +
            `- Use - for decrease (e.g., -20)\n` +
            `- Use = for exact amount (e.g., =200)`
        );

        if (adjustment === null) return; // User cancelled

        let newAmount;
        if (adjustment.startsWith('+')) {
            newAmount = currentTotal + parseFloat(adjustment.substring(1));
        } else if (adjustment.startsWith('-')) {
            newAmount = currentTotal - parseFloat(adjustment.substring(1));
        } else if (adjustment.startsWith('=')) {
            newAmount = parseFloat(adjustment.substring(1));
        } else {
            newAmount = parseFloat(adjustment);
        }

        if (isNaN(newAmount) || newAmount < 0) {
            alert('Invalid amount entered');
            return;
        }

        if (confirm(`Update total from ₹${currentTotal.toFixed(2)} to ₹${newAmount.toFixed(2)}?`)) {
            // Send update request
            console.log('Updating transaction:', {
                transaction_id: transactionId,
                new_amount: newAmount,
                url: `${SITE_URL}/api/transactions.php`
            });

            fetch(`${SITE_URL}/api/transactions.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'update_amount',
                        transaction_id: transactionId,
                        new_amount: newAmount
                    })
                })
                .then(res => {
                    console.log('Response status:', res.status);
                    console.log('Response headers:', res.headers);

                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }

                    return res.text(); // Get as text first to see raw response
                })
                .then(text => {
                    console.log('Raw response:', text);

                    try {
                        const result = JSON.parse(text);
                        console.log('Parsed response:', result);

                        if (result.success) {
                            alert('Transaction amount updated successfully');
                            loadTransactions(); // Reload transactions
                        } else {
                            alert('Failed to update transaction: ' + result.message);
                        }
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        alert('Invalid response from server: ' + text);
                    }
                })
                .catch(err => {
                    console.error('Error updating transaction:', err);
                    alert('Error updating transaction: ' + err.message);
                });
        }
    }

    function viewTransaction(transactionId) {
        // Find the transaction data
        const transaction = allTransactions.find(t => t.id === transactionId);
        if (!transaction) {
            alert('Transaction not found');
            return;
        }

        const date = new Date(transaction.created_at || transaction.transaction_date).toLocaleString();

        const details = `
Transaction Details:

ID: ${transaction.id}
Customer: ${transaction.customer_name}
Console: ${transaction.console_name || '-'}
Duration: ${transaction.total_duration_minutes || transaction.duration || 0} minutes
Start Time: ${transaction.start_time || '-'}
End Time: ${transaction.end_time || '-'}

Amounts:
- Gaming: ₹${parseFloat(transaction.gaming_amount || 0).toFixed(2)}
- Food & Drinks: ₹${parseFloat(transaction.fandd_amount || transaction.food_amount || 0).toFixed(2)}
- Subtotal: ₹${parseFloat((transaction.gaming_amount || 0) + (transaction.fandd_amount || transaction.food_amount || 0)).toFixed(2)}
- Tax: ₹${parseFloat(transaction.tax_amount || 0).toFixed(2)}
- Total: ₹${parseFloat(transaction.total_amount || 0).toFixed(2)}

Payment: ${transaction.payment_method || transaction.payment_status || 'pending'}
Created: ${date}
Created By: ${transaction.user_name || '-'}
        `;

        alert(details);
    }

    function deleteTransaction(transactionId) {
        // Find the transaction data
        const transaction = allTransactions.find(t => t.id === transactionId);
        if (!transaction) {
            alert('Transaction not found');
            return;
        }

        // Confirmation dialog
        const confirmMessage = `Are you sure you want to delete this transaction?\n\n` +
            `Transaction ID: ${transaction.id}\n` +
            `Customer: ${transaction.customer_name}\n` +
            `Amount: ₹${parseFloat(transaction.total_amount || 0).toFixed(2)}\n` +
            `Date: ${new Date(transaction.created_at || transaction.transaction_date).toLocaleString()}\n\n` +
            `This action cannot be undone!`;

        if (!confirm(confirmMessage)) {
            return;
        }

        // Send delete request
        fetch(`${SITE_URL}/api/transactions.php`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'delete',
                    transaction_id: transactionId
                })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.text();
            })
            .then(text => {
                try {
                    const result = JSON.parse(text);
                    if (result.success) {
                        alert('Transaction deleted successfully');
                        loadTransactions(); // Reload transactions
                    } else {
                        alert('Failed to delete transaction: ' + result.message);
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    alert('Invalid response from server: ' + text);
                }
            })
            .catch(err => {
                console.error('Error deleting transaction:', err);
                alert('Error deleting transaction: ' + err.message);
            });
    }

    // Event Listeners
    document.getElementById('search-input').addEventListener('input', function(e) {
        const clearBtn = document.getElementById('clear-search');
        clearBtn.style.display = e.target.value ? 'flex' : 'none';
        applyFilters();
    });

    document.getElementById('clear-search').addEventListener('click', function() {
        document.getElementById('search-input').value = '';
        this.style.display = 'none';
        applyFilters();
    });

    document.getElementById('console-filter').addEventListener('change', applyFilters);
    document.getElementById('payment-filter').addEventListener('change', applyFilters);

    document.getElementById('apply-filters').addEventListener('click', loadTransactions);

    document.getElementById('reset-filters').addEventListener('click', function() {
        initializeDates();
        document.getElementById('search-input').value = '';
        document.getElementById('console-filter').value = '';
        document.getElementById('payment-filter').value = '';
        document.getElementById('clear-search').style.display = 'none';
        loadTransactions();
    });

    document.getElementById('items-per-page').addEventListener('change', function(e) {
        itemsPerPage = e.target.value === 'all' ? 'all' : parseInt(e.target.value);
        currentPage = 1;
        displayTransactions();
    });

    document.getElementById('first-page').addEventListener('click', function() {
        currentPage = 1;
        displayTransactions();
    });

    document.getElementById('prev-page').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            displayTransactions();
        }
    });

    document.getElementById('next-page').addEventListener('click', function() {
        const totalPages = itemsPerPage === 'all' ? 1 : Math.ceil(filteredTransactions.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayTransactions();
        }
    });

    document.getElementById('last-page').addEventListener('click', function() {
        const totalPages = itemsPerPage === 'all' ? 1 : Math.ceil(filteredTransactions.length / itemsPerPage);
        currentPage = totalPages;
        displayTransactions();
    });

    // Initial load
    initializeDates();
    loadTransactions();

    // Auto-refresh every 30 seconds
    setInterval(loadTransactions, 30000);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>