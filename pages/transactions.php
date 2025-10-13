<?php
$pageTitle = 'Transactions';
$currentPage = 'transactions';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="transactions" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Transaction History</h2>
    </div>

    <div class="card">

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
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="transactions-table-body">
                    <!-- Rows will be loaded here -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Grand Totals:</td>
                        <td id="gaming-total">₹0.00</td>
                        <td id="food-total">₹0.00</td>
                        <td id="grand-total">₹0.00</td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</section>

<style>
    /* Beautiful Enhanced Table Styles */
    .table-container {
        overflow-x: auto;
        overflow-y: visible;
        border-radius: 12px;
        border: none;

        margin: 20px 0;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        min-width: 1400px;
        background: #1a1a1a;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #333;
    }

    .data-table thead {
        background: #2d2d2d;
        color: white;
        position: relative;
    }

    .data-table thead::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
        backdrop-filter: blur(10px);
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
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
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
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        vertical-align: middle;
        color: #e2e8f0;
        font-size: 13px;
        transition: all 0.3s ease;
        height: 48px;
    }

    .data-table tbody tr {
        background: #1a1a1a;
        transition: all 0.3s ease;
    }

    .data-table tbody tr:hover {
        background: #2a2a2a;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .data-table tbody tr:nth-child(even) {
        background: #1f1f1f;
    }

    .data-table tbody tr:nth-child(even):hover {
        background: #2f2f2f;
    }

    .data-table tfoot {
        background: #2d2d2d;
        color: white;
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
        background: linear-gradient(135deg, rgba(255, 107, 107, 0.9), rgba(238, 90, 36, 0.9));
        backdrop-filter: blur(10px);
        z-index: -1;
    }

    .data-table tfoot td {
        border-bottom: none;
        padding: 16px 12px;
        font-size: 14px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    /* Compact Action Buttons */
    .action-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: nowrap;
        justify-content: flex-start;
        align-items: center;
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
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
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
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
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
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(237, 137, 54, 0.4);
    }

    .btn-info {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #3182ce 0%, #2c5aa0 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(66, 153, 225, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(229, 62, 62, 0.4);
    }

    .btn-secondary {
        background: #495057;
        color: white;
        border: 1px solid #495057;
        font-size: 9px;
        padding: 4px 8px;
        min-width: 35px;
        height: 22px;
    }

    .btn-secondary:hover {
        background: #343a40;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(73, 80, 87, 0.4);
    }

    /* Beautiful Transaction Toolbar */
    .transaction-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px 12px 0 0;
        backdrop-filter: blur(10px);
        position: relative;
    }

    .transaction-toolbar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(26, 26, 46, 0.8), rgba(22, 33, 62, 0.8));
        border-radius: 12px 12px 0 0;
        z-index: -1;
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
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        font-size: 14px;
        width: 280px;
        background: rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .search-input::placeholder {
        color: rgba(226, 232, 240, 0.6);
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        background: rgba(255, 255, 255, 0.08);
    }

    .clear-btn {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        font-size: 18px;
        color: rgba(226, 232, 240, 0.6);
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
        color: #ff6b6b;
        background: rgba(255, 107, 107, 0.1);
        transform: scale(1.1);
    }

    /* Enhanced stats display */
    .transaction-toolbar span {
        padding: 8px 15px !important;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2)) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(10px) !important;
        color: #e2e8f0 !important;
        border-radius: 8px !important;
    }

    /* Enhanced select styling */
    #items-per-page {
        padding: 8px 12px;
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
        font-size: 14px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    #items-per-page:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    #items-per-page option {
        background: #1a1a2e;
        color: #e2e8f0;
    }

    /* Beautiful Pagination */
    .pagination-container {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0 0 12px 12px;
        backdrop-filter: blur(10px);
        position: relative;
    }

    .pagination-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(26, 26, 46, 0.8), rgba(22, 33, 62, 0.8));
        border-radius: 0 0 12px 12px;
        z-index: -1;
    }

    .pagination-btn {
        padding: 8px 14px;
        margin: 0 3px;
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
    }

    .pagination-btn:hover:not(:disabled) {
        background: rgba(102, 126, 234, 0.2);
        border-color: #667eea;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .pagination-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: rgba(255, 255, 255, 0.02);
    }

    /* Pagination info styling */
    .pagination-info {
        color: #e2e8f0;
        font-weight: 500;
    }

    /* Enhanced section styling */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding: 20px 0;
    }

    .section-title {
        color: #e2e8f0;
        font-size: 28px;
        font-weight: 700;
        margin: 0;
      
    }

    .section-header span {
        padding: 12px 20px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        font-weight: 600;
        color: #e2e8f0;
        backdrop-filter: blur(10px);
    }

    /* Card styling */
    .card {
        background: linear-gradient(145deg, #1a1a2e, #16213e);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        overflow: hidden;
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

    // Load today's transactions
    function loadTransactions() {
        const today = new Date().toISOString().split('T')[0];
        let url = `${SITE_URL}/api/transactions.php?action=list&start_date=${today}&end_date=${today}`;

        fetch(url)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    allTransactions = result.data;
                    filteredTransactions = result.data;
                    displayTransactions();
                }
            })
            .catch(err => console.error('Error loading transactions:', err));
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
        tbody.innerHTML = '';

        let gamingTotal = 0;
        let foodTotal = 0;
        let grandTotal = 0;

        if (filteredTransactions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="11" style="text-align: center; padding: 20px; color: #999;">No transactions found</td></tr>';
            document.getElementById('gaming-total').textContent = '₹0.00';
            document.getElementById('food-total').textContent = '₹0.00';
            document.getElementById('grand-total').textContent = '₹0.00';
            return;
        }

        // Display all transactions (no pagination)
        filteredTransactions.forEach((txn, index) => {
            const tr = document.createElement('tr');
            const globalIndex = index + 1;

            tr.innerHTML = `
            <td>${globalIndex}</td>
            <td>${txn.customer_name}</td>
            <td>${txn.console_name || '-'}</td>
            <td>${formatDuration(txn.total_duration_minutes || txn.duration || 0)}</td>
            <td>₹${parseFloat(txn.gaming_amount || 0).toFixed(2)}</td>
            <td>₹${parseFloat(txn.fandd_amount || txn.food_amount || 0).toFixed(2)}</td>
            <td>₹${parseFloat(txn.total_amount || 0).toFixed(2)}</td>
            <td>${txn.payment_method || txn.payment_status || 'pending'}</td>
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

        // Calculate totals from all filtered transactions
        filteredTransactions.forEach(txn => {
            gamingTotal += parseFloat(txn.gaming_amount || 0);
            foodTotal += parseFloat(txn.fandd_amount || txn.food_amount || 0);
            grandTotal += parseFloat(txn.total_amount || 0);
        });

        document.getElementById('gaming-total').textContent = '₹' + gamingTotal.toFixed(2);
        document.getElementById('food-total').textContent = '₹' + foodTotal.toFixed(2);
        document.getElementById('grand-total').textContent = '₹' + grandTotal.toFixed(2);
    }

    // Pagination functions removed - no pagination in UI


    // Search functionality removed - no search elements in UI
    // Items per page functionality removed - no pagination in UI

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
                        <img src="assets/logo.png" alt="GameBot Gaming Cafe Logo" class="receipt-logo">
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

    // Initial load
    loadTransactions();

    // Auto-refresh every 30 seconds
    setInterval(loadTransactions, 30000);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>