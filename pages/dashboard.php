<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="dashboard" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Today's Dashboard</h2>
        <div class="dashboard-controls">
            <span style="padding: 8px 15px; background: #e3f2fd; border-radius: 5px; font-weight: 600; color: #1976d2;">
                📅 <?php echo date('F d, Y'); ?>
            </span>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="metrics-grid">
        <div class="metric-card card">
            <div class="metric-icon">⏰</div>
            <div class="metric-content">
                <h3 class="metric-value" id="system-uptime">--</h3>
                <p class="metric-label">Current System Uptime</p>
            </div>
        </div>
        <div class="metric-card card">
            <div class="metric-icon">🕒</div>
            <div class="metric-content">
                <h3 class="metric-value" id="system-total-time">--</h3>
                <p class="metric-label">System Total Time</p>
            </div>
        </div>
        <div class="metric-card card">
            <div class="metric-icon">₹</div>
            <div class="metric-content">
                <h3 class="metric-value" id="today-revenue">₹0.00</h3>
                <p class="metric-label">Revenue</p>
            </div>
        </div>
        <div class="metric-card card">
            <div class="metric-icon">👥</div>
            <div class="metric-content">
                <h3 class="metric-value" id="today-customers">0</h3>
                <p class="metric-label">Customers</p>
            </div>
        </div>
        <div class="metric-card card">
            <div class="metric-icon">🎮</div>
            <div class="metric-content">
                <h3 class="metric-value" id="active-consoles">0</h3>
                <p class="metric-label">Current Active Consoles</p>
            </div>
        </div>
        <div class="metric-card card">
            <div class="metric-icon">⏳</div>
            <div class="metric-content">
                <h3 class="metric-value" id="peak-hours">--</h3>
                <p class="metric-label">Peak Hours</p>
            </div>
        </div>
        <div class="metric-card card">
            <div class="metric-icon">🕹️</div>
            <div class="metric-content">
                <h3 class="metric-value" id="avg-session">--</h3>
                <p class="metric-label">Avg Session</p>
            </div>
        </div>
        <div class="metric-card card">
            <div class="metric-icon">📈</div>
            <div class="metric-content">
                <h3 class="metric-value" id="utilization">--</h3>
                <p class="metric-label">Utilization %</p>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <section id="charts-section">
        <div class="charts-grid">
            <!-- Revenue Chart -->
            <div class="chart-card">
                <h3>Revenue Chart</h3>
                <canvas id="revenue-chart"></canvas>
            </div>

            <!-- Peak Hour Usage -->
            <div class="chart-card">
                <h3>Peak Hour Usage</h3>
                <canvas id="peak-hour-chart"></canvas>
            </div>

            <!-- Customer Trend -->
            <div class="chart-card">
                <h3>Customer Trend</h3>
                <canvas id="customer-trend-chart"></canvas>
            </div>

            <!-- Revenue Split -->
            <div class="chart-card">
                <h3>Revenue Split</h3>
                <canvas id="revenue-split-chart"></canvas>
            </div>

            <!-- Console Utilization -->
            <div class="chart-card">
                <h3>Console Utilization</h3>
                <canvas id="console-utilization-chart"></canvas>
            </div>
        </div>
    </section>

    <section>
        <div class="card activity-log-card">
            <h3>Activity Logs</h3>
            <ul id="activity-log" class="activity-log"></ul>
        </div>
    </section>
</section>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    let currentDashboardPeriod = 'daily'; // Fixed to daily (today only)
    let charts = {}; // Store chart instances

    // Load dashboard data
    function loadDashboardStats() {
        fetch(`${SITE_URL}/api/dashboard.php?action=stats&period=${currentDashboardPeriod}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const data = result.data;
                    document.getElementById('today-revenue').textContent = '₹' + parseFloat(data.revenue || 0).toFixed(2);
                    document.getElementById('today-customers').textContent = data.customers || 0;
                    document.getElementById('active-consoles').textContent = data.active_consoles || 0;
                    document.getElementById('peak-hours').textContent = data.peak_hour || '--';
                    document.getElementById('avg-session').textContent = (data.avg_session || 0) + ' mins';
                    document.getElementById('utilization').textContent = (data.utilization || 0) + '%';

                    // Update system uptime and total time
                    document.getElementById('system-uptime').textContent = '24/7';
                    document.getElementById('system-total-time').textContent = 'Active';
                }
            })
            .catch(err => console.error('Error loading stats:', err));

        // Load charts data
        loadCharts();
    }

    // Load charts
    function loadCharts() {
        fetch(`${SITE_URL}/api/dashboard.php?action=charts&period=${currentDashboardPeriod}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const data = result.data;

                    // Revenue Chart
                    updateLineChart('revenue-chart', 'Revenue Trend', data.revenue_chart || [], '₹');

                    // Customer Trend Chart
                    updateLineChart('customer-trend-chart', 'Customer Trend', data.customer_chart || [], '');

                    // Peak Hour Chart (use revenue data)
                    updateBarChart('peak-hour-chart', 'Peak Hours', data.revenue_chart || [], '₹');

                    // Create revenue split pie chart
                    createRevenueSplitChart();

                    // Create console utilization chart
                    createConsoleUtilizationChart();
                }
            })
            .catch(err => console.error('Error loading charts:', err));
    }

    // Update Line Chart
    function updateLineChart(canvasId, label, data, prefix = '') {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;

        // Destroy existing chart
        if (charts[canvasId]) {
            charts[canvasId].destroy();
        }

        const labels = data.map(item => {
            if (currentDashboardPeriod === 'daily') return item.label + ':00';
            if (currentDashboardPeriod === 'monthly') return 'Day ' + item.label;
            return 'Month ' + item.label;
        });

        const values = data.map(item => parseFloat(item.value || 0));

        charts[canvasId] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return prefix + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return prefix + value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Update Bar Chart
    function updateBarChart(canvasId, label, data, prefix = '') {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;

        if (charts[canvasId]) {
            charts[canvasId].destroy();
        }

        const labels = data.map(item => {
            if (currentDashboardPeriod === 'daily') return item.label + ':00';
            if (currentDashboardPeriod === 'monthly') return 'Day ' + item.label;
            return 'Month ' + item.label;
        });

        const values = data.map(item => parseFloat(item.value || 0));

        charts[canvasId] = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values,
                    backgroundColor: '#28a745',
                    borderColor: '#218838',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return prefix + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return prefix + value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Create Revenue Split Pie Chart
    function createRevenueSplitChart() {
        fetch(`${SITE_URL}/api/dashboard.php?action=stats&period=${currentDashboardPeriod}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const data = result.data;
                    const ctx = document.getElementById('revenue-split-chart');
                    if (!ctx) return;

                    if (charts['revenue-split-chart']) {
                        charts['revenue-split-chart'].destroy();
                    }

                    charts['revenue-split-chart'] = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Gaming', 'F&D', 'Tax'],
                            datasets: [{
                                data: [
                                    parseFloat(data.gaming_revenue || 0),
                                    parseFloat(data.fandd_revenue || 0),
                                    parseFloat(data.tax_collected || 0)
                                ],
                                backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ₹' + context.parsed.toFixed(2);
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
    }

    // Create Console Utilization Chart
    function createConsoleUtilizationChart() {
        fetch(`${SITE_URL}/api/dashboard.php?action=stats&period=${currentDashboardPeriod}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const data = result.data;
                    const ctx = document.getElementById('console-utilization-chart');
                    if (!ctx) return;

                    if (charts['console-utilization-chart']) {
                        charts['console-utilization-chart'].destroy();
                    }

                    const active = parseInt(data.active_consoles || 0);
                    const utilization = parseFloat(data.utilization || 0);
                    const available = 100 - utilization;

                    charts['console-utilization-chart'] = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['In Use', 'Available'],
                            datasets: [{
                                data: [utilization, available],
                                backgroundColor: ['#dc3545', '#28a745'],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed.toFixed(1) + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
    }

    // Load activity logs
    function loadActivityLogs() {
        fetch(`${SITE_URL}/api/dashboard.php?action=activity-logs&limit=10`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const logList = document.getElementById('activity-log');
                    logList.innerHTML = '';

                    result.data.forEach(log => {
                        const li = document.createElement('li');
                        const time = new Date(log.created_at).toLocaleString();
                        li.innerHTML = `<strong>${log.user_name || 'System'}</strong> - ${log.description || log.action} <span class="log-time">${time}</span>`;
                        logList.appendChild(li);
                    });
                }
            })
            .catch(err => console.error('Error loading logs:', err));
    }

    // Initial load (today's data only)
    loadDashboardStats();
    loadActivityLogs();

    // Refresh every 30 seconds
    setInterval(() => {
        loadDashboardStats();
        loadActivityLogs();
    }, 30000);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>