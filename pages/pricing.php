<?php
$pageTitle = 'Price Management';
$currentPage = 'pricing';
require_once __DIR__ . '/../includes/header.php';

// Fetch pricing data
$db = getDB();
$regularPricing = [];
$vipPricing = [];

$stmt = $db->prepare("SELECT * FROM pricing WHERE rate_type = 'regular' ORDER BY player_count ASC");
$stmt->execute();
while ($row = $result->fetch_assoc()) {
    $regularPricing[] = $row;
}

$stmt = $db->prepare("SELECT * FROM pricing WHERE rate_type = 'vip' ORDER BY player_count ASC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $vipPricing[] = $row;
}

$peakMultiplier = '1.2';
$weekendMultiplier = '1.1';
$peakHours = '';
?>

<section id="price-management" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Price Management</h2>
        <div class="header-actions">
            <button id="refresh-pricing-btn" class="btn btn--secondary">🔄 Refresh</button>
            <button id="add-pricing-btn" class="btn btn--primary">Add Pricing</button>
        </div>
    </div>

    <div class="pricing-management-grid" id="pricing-grid">
        <!-- Regular Gaming Rates -->
        <div class="card">
            <div class="card__body">
                <div class="pricing-section-header">
                    <h3>Regular Gaming Rates</h3>
                    <button class="btn btn--sm btn--outline" onclick="editRegularRates()">✏️ Edit</button>
                </div>
                <div class="pricing-table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Players</th>
                                <th>15 Mins</th>
                                <th>30 Mins</th>
                                <th>45 Mins</th>
                                <th>60 Mins</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="regular-pricing">
                            <?php foreach ($regularPricing as $rate): ?>
                                <tr>
                                    <td><?php echo $rate['player_count']; ?> Player(s)</td>
                                    <td>₹<?php echo number_format($rate['duration_15'], 2); ?></td>
                                    <td>₹<?php echo number_format($rate['duration_30'], 2); ?></td>
                                    <td>₹<?php echo number_format($rate['duration_45'], 2); ?></td>
                                    <td>₹<?php echo number_format($rate['duration_60'], 2); ?></td>
                                    <td>
                                        <button class="btn btn--sm btn--primary" onclick="editPricingEntry(<?php echo $rate['id']; ?>)">Edit</button>
                                        <button class="btn btn--sm btn--danger" onclick="deletePricingEntry(<?php echo $rate['id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- VIP Room Rates -->
        <div class="card">
            <div class="card__body">
                <div class="pricing-section-header">
                    <h3>VIP Room Rates</h3>
                    <button class="btn btn--sm btn--outline" onclick="editVipRates()">✏️ Edit</button>
                </div>
                <div class="pricing-table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Players</th>
                                <th>15 Mins</th>
                                <th>30 Mins</th>
                                <th>45 Mins</th>
                                <th>60 Mins</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="vip-pricing">
                            <?php foreach ($vipPricing as $rate): ?>
                                <tr>
                                    <td><?php echo $rate['player_count']; ?> Player(s)</td>
                                    <td>₹<?php echo number_format($rate['duration_15'], 2); ?></td>
                                    <td>₹<?php echo number_format($rate['duration_30'], 2); ?></td>
                                    <td>₹<?php echo number_format($rate['duration_45'], 2); ?></td>
                                    <td>₹<?php echo number_format($rate['duration_60'], 2); ?></td>
                                    <td>
                                        <button class="btn btn--sm btn--primary" onclick="editPricingEntry(<?php echo $rate['id']; ?>)">Edit</button>
                                        <button class="btn btn--sm btn--danger" onclick="deletePricingEntry(<?php echo $rate['id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Pricing Modal -->
<div id="edit-pricing-modal" class="modal hidden">
    <div class="modal-content">
        <h3 id="pricing-modal-title">Edit Pricing</h3>
        <form id="pricing-form">
            <input type="hidden" id="pricing-id">
            <div class="form-group">
                <label class="form-label">Rate Type *</label>
                <select class="form-control" id="pricing-rate-type" required>
                    <option value="">Select rate type</option>
                    <option value="regular">Regular</option>
                    <option value="vip">VIP</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Player Count *</label>
                <select class="form-control" id="pricing-player-count" required>
                    <option value="">Select player count</option>
                    <option value="1">1 Player</option>
                    <option value="2">2 Players</option>
                    <option value="3">3 Players</option>
                    <option value="4">4 Players</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">15 Minutes Price *</label>
                <input type="number" class="form-control" id="pricing-15" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">30 Minutes Price *</label>
                <input type="number" class="form-control" id="pricing-30" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">45 Minutes Price *</label>
                <input type="number" class="form-control" id="pricing-45" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">60 Minutes Price *</label>
                <input type="number" class="form-control" id="pricing-60" min="0" step="0.01" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="cancel-pricing">Cancel</button>
                <button type="submit" class="btn btn--primary">Save Pricing</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Load pricing data dynamically
    function loadPricingData() {
        fetch(`${SITE_URL}/api/pricing.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    updatePricingTables(result.data);
                } else {
                    console.error('Error loading pricing:', result.message);
                }
            })
            .catch(error => {
                console.error('Network error loading pricing:', error);
            });
    }

    // Update pricing tables with dynamic data
    function updatePricingTables(pricingData) {
        const regularPricing = pricingData.filter(item => item.rate_type === 'regular');
        const vipPricing = pricingData.filter(item => item.rate_type === 'vip');

        updatePricingTable('regular', regularPricing);
        updatePricingTable('vip', vipPricing);
    }

    // Update individual pricing table
    function updatePricingTable(type, pricingData) {
        const tableBody = document.querySelector(`#${type}-pricing tbody`);
        if (!tableBody) return;

        tableBody.innerHTML = '';

        pricingData.forEach(rate => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${rate.player_count} Player(s)</td>
                <td>₹${parseFloat(rate.duration_15 || 0).toFixed(2)}</td>
                <td>₹${parseFloat(rate.duration_30 || 0).toFixed(2)}</td>
                <td>₹${parseFloat(rate.duration_45 || 0).toFixed(2)}</td>
                <td>₹${parseFloat(rate.duration_60 || 0).toFixed(2)}</td>
                <td>
                    <button class="btn btn--sm btn--primary" onclick="editPricingEntry(${rate.id})">Edit</button>
                    <button class="btn btn--sm btn--danger" onclick="deletePricingEntry(${rate.id})">Delete</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Edit pricing entry
    function editPricingEntry(id) {
        fetch(`${SITE_URL}/api/pricing.php?action=get&id=${id}`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const pricing = result.data;
                    document.getElementById('pricing-modal-title').textContent = 'Edit Pricing';
                    document.getElementById('pricing-id').value = pricing.id;
                    document.getElementById('pricing-rate-type').value = pricing.rate_type;
                    document.getElementById('pricing-player-count').value = pricing.player_count;
                    document.getElementById('pricing-15').value = pricing.duration_15 || 0;
                    document.getElementById('pricing-30').value = pricing.duration_30 || 0;
                    document.getElementById('pricing-45').value = pricing.duration_45 || 0;
                    document.getElementById('pricing-60').value = pricing.duration_60 || 0;
                    document.getElementById('edit-pricing-modal').classList.remove('hidden');
                }
            });
    }

    // Delete pricing entry
    function deletePricingEntry(id) {
        if (confirm('Are you sure you want to delete this pricing entry?')) {
            fetch(`${SITE_URL}/api/pricing.php?action=delete&id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        loadPricingData();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    }

    // Add new pricing entry
    function addPricingEntry() {
        document.getElementById('pricing-modal-title').textContent = 'Add New Pricing';
        document.getElementById('pricing-form').reset();
        document.getElementById('edit-pricing-modal').classList.remove('hidden');
    }

    function editRegularRates() {
        // This function can be used to show regular rates in a different view if needed
        loadPricingData();
    }

    // Event listeners
    document.getElementById('add-pricing-btn').addEventListener('click', addPricingEntry);
    document.getElementById('refresh-pricing-btn').addEventListener('click', loadPricingData);

    document.getElementById('pricing-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const pricingId = document.getElementById('pricing-id').value;
        const isEdit = pricingId !== '';

        const data = {
            rate_type: document.getElementById('pricing-rate-type').value,
            player_count: parseInt(document.getElementById('pricing-player-count').value),
            duration_15: parseFloat(document.getElementById('pricing-15').value),
            duration_30: parseFloat(document.getElementById('pricing-30').value),
            duration_45: parseFloat(document.getElementById('pricing-45').value),
            duration_60: parseFloat(document.getElementById('pricing-60').value)
        };

        const url = isEdit ?
            `${SITE_URL}/api/pricing.php?action=update&id=${pricingId}` :
            `${SITE_URL}/api/pricing.php?action=create`;

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    document.getElementById('edit-pricing-modal').classList.add('hidden');
                    loadPricingData();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    });

    document.getElementById('cancel-pricing').addEventListener('click', () => {
        document.getElementById('edit-pricing-modal').classList.add('hidden');
    });

    // Initial load
    loadPricingData();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>