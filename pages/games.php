<?php
$pageTitle = 'Game Management';
$currentPage = 'games';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="games" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Game Management</h2>
        <?php if (Auth::hasRole('Admin') || Auth::hasRole('Manager')): ?>
            <button class="btn btn--primary" id="add-game-btn">Add New Game</button>
        <?php endif; ?>
    </div>
    <div class="games-grid" id="games-grid">
        <!-- Games will be loaded here -->
    </div>
</section>

<!-- Add Game Modal -->
<div id="add-game-modal" class="modal hidden">
    <div class="modal-content">
        <h3 id="game-modal-title">Add New Game</h3>
        <form id="game-form">
            <input type="hidden" id="game-id">
            <div class="form-group">
                <label class="form-label">Game Name *</label>
                <input type="text" class="form-control" id="game-name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category *</label>
                <select class="form-control" id="game-category" required>
                    <option value="">Select category</option>
                    <option value="FPS">FPS</option>
                    <option value="Sports">Sports</option>
                    <option value="Action">Action</option>
                    <option value="Racing">Racing</option>
                    <option value="RPG">RPG</option>
                    <option value="Strategy">Strategy</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Developer *</label>
                <input type="text" class="form-control" id="game-developer" required>
            </div>
            <div class="form-group">
                <label class="form-label">Rating</label>
                <input type="number" class="form-control" id="game-rating" min="1" max="5" step="0.1">
            </div>
            <div class="form-group">
                <label class="form-label">Release Date</label>
                <input type="date" class="form-control" id="game-release">
            </div>
            <div class="form-group">
                <label class="form-label">Available on Consoles *</label>
                <div class="console-selection">
                    <div class="console-selection-header">
                        <button type="button" class="btn btn--sm btn--outline" id="select-all-consoles">Select All</button>
                        <button type="button" class="btn btn--sm btn--outline" id="deselect-all-consoles">Deselect All</button>
                    </div>
                    <div class="console-grid" id="console-selection-grid">
                        <!-- Consoles will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="cancel-game">Cancel</button>
                <button type="submit" class="btn btn--primary">Save Game</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Load games
    function loadGames() {
        fetch(`${SITE_URL}/api/games.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const grid = document.getElementById('games-grid');
                    grid.innerHTML = '';

                    result.data.forEach(game => {
                        const card = document.createElement('div');
                        card.className = 'game-card card';
                        let actionButtons = '';
                        if (USER_ROLE === 'Admin' || USER_ROLE === 'Manager') {
                            actionButtons = `
                                <div class="card-actions">
                                    <button class="btn btn--sm btn--primary" onclick="editGame(${game.id})">
                                        <span class="btn-icon">✏️</span>
                                        Edit
                                    </button>
                                    <button class="btn btn--sm btn--danger" onclick="deleteGame(${game.id})">
                                        <span class="btn-icon">🗑️</span>
                                        Delete
                                    </button>
                                </div>
                            `;
                        }

                        // Get console names for display
                        const consoleNames = getConsoleNames(game.assigned_consoles || []);

                        card.innerHTML = `
                        <div class="game-header">
                            <div class="game-title-section">
                                <h3 class="game-title">${game.name}</h3>
                                <div class="game-category-badge">${game.category}</div>
                            </div>
                            <div class="game-rating">
                                ${game.rating ? '⭐'.repeat(Math.floor(game.rating)) + (game.rating % 1 ? '☆' : '') : 'No Rating'}
                            </div>
                        </div>
                        <div class="game-content">
                            <div class="game-info">
                                <div class="info-item">
                                    <span class="info-label">Developer:</span>
                                    <span class="info-value">${game.developer}</span>
                                </div>
                                ${game.release_date ? `
                                <div class="info-item">
                                    <span class="info-label">Released:</span>
                                    <span class="info-value">${new Date(game.release_date).toLocaleDateString()}</span>
                                </div>
                                ` : ''}
                            </div>
                            <div class="game-consoles">
                                <div class="consoles-label">Available on:</div>
                                <div class="consoles-list">
                                    ${consoleNames.length > 0 ? consoleNames.map(name => `<span class="console-tag">${name}</span>`).join('') : '<span class="no-consoles">No consoles assigned</span>'}
                                </div>
                            </div>
                        </div>
                        ${actionButtons}
                    `;
                        grid.appendChild(card);
                    });
                }
            });
    }

    // Global variables
    let availableConsoles = [];
    let consoleMap = {};

    // Load available consoles
    function loadConsoles() {
        fetch(`${SITE_URL}/api/consoles.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    availableConsoles = result.data;
                    consoleMap = {};
                    result.data.forEach(console => {
                        consoleMap[console.id] = console.name;
                    });
                }
            })
            .catch(error => {
                console.error('Error loading consoles:', error);
            });
    }

    // Get console names from IDs
    function getConsoleNames(consoleIds) {
        return consoleIds.map(id => consoleMap[id] || `Console ${id}`).filter(Boolean);
    }

    // Load console selection grid
    function loadConsoleSelection(selectedConsoles = []) {
        const grid = document.getElementById('console-selection-grid');
        grid.innerHTML = '';

        availableConsoles.forEach(console => {
            const isSelected = selectedConsoles.includes(console.id);
            const consoleCard = document.createElement('div');
            consoleCard.className = `console-selection-card ${isSelected ? 'selected' : ''}`;
            consoleCard.innerHTML = `
                <div class="console-checkbox">
                    <input type="checkbox" id="console-${console.id}" value="${console.id}" ${isSelected ? 'checked' : ''}>
                    <label for="console-${console.id}"></label>
                </div>
                <div class="console-info">
                    <div class="console-name">${console.name}</div>
                    <div class="console-status ${console.status.toLowerCase()}">${console.status}</div>
                </div>
            `;
            grid.appendChild(consoleCard);
        });
    }

    // Add game button
    const addGameBtn = document.getElementById('add-game-btn');
    if (addGameBtn) {
        addGameBtn.addEventListener('click', () => {
            document.getElementById('game-modal-title').textContent = 'Add New Game';
            document.getElementById('game-form').reset();
            document.getElementById('game-id').value = '';
            loadConsoleSelection();
            document.getElementById('add-game-modal').classList.remove('hidden');
        });
    }

    // Cancel
    document.getElementById('cancel-game').addEventListener('click', () => {
        document.getElementById('add-game-modal').classList.add('hidden');
    });

    // Form submit
    document.getElementById('game-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const id = document.getElementById('game-id').value;

        // Get selected consoles
        const selectedConsoles = [];
        document.querySelectorAll('#console-selection-grid input[type="checkbox"]:checked').forEach(checkbox => {
            selectedConsoles.push(parseInt(checkbox.value));
        });

        if (selectedConsoles.length === 0) {
            alert('Please select at least one console for this game.');
            return;
        }

        const data = {
            name: document.getElementById('game-name').value,
            category: document.getElementById('game-category').value,
            developer: document.getElementById('game-developer').value,
            rating: document.getElementById('game-rating').value || null,
            release_date: document.getElementById('game-release').value || null,
            branch_id: 1,
            consoles: selectedConsoles
        };

        const action = id ? 'update' : 'create';
        if (id) data.id = id;

        fetch(`${SITE_URL}/api/games.php?action=${action}`, {
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
                    document.getElementById('add-game-modal').classList.add('hidden');
                    loadGames();
                } else {
                    alert('Error: ' + result.message);
                }
            });
    });

    // Edit game
    function editGame(id) {
        fetch(`${SITE_URL}/api/games.php?action=list`)
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const game = result.data.find(g => g.id == id);
                    if (game) {
                        document.getElementById('game-modal-title').textContent = 'Edit Game';
                        document.getElementById('game-id').value = game.id;
                        document.getElementById('game-name').value = game.name;
                        document.getElementById('game-category').value = game.category;
                        document.getElementById('game-developer').value = game.developer;
                        document.getElementById('game-rating').value = game.rating;
                        document.getElementById('game-release').value = game.release_date;

                        // Load console selection with current assignments
                        loadConsoleSelection(game.assigned_consoles || []);
                        document.getElementById('add-game-modal').classList.remove('hidden');
                    }
                }
            });
    }

    // Delete game
    function deleteGame(id) {
        if (confirm('Are you sure you want to delete this game?')) {
            fetch(`${SITE_URL}/api/games.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        loadGames();
                    } else {
                        alert('Error: ' + result.message);
                    }
                });
        }
    }

    // Select all consoles
    document.getElementById('select-all-consoles').addEventListener('click', () => {
        document.querySelectorAll('#console-selection-grid input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = true;
            checkbox.closest('.console-selection-card').classList.add('selected');
        });
    });

    // Deselect all consoles
    document.getElementById('deselect-all-consoles').addEventListener('click', () => {
        document.querySelectorAll('#console-selection-grid input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
            checkbox.closest('.console-selection-card').classList.remove('selected');
        });
    });

    // Handle console selection changes
    document.addEventListener('change', (e) => {
        if (e.target.matches('#console-selection-grid input[type="checkbox"]')) {
            const card = e.target.closest('.console-selection-card');
            if (e.target.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        }
    });

    // Initial load
    loadConsoles();
    loadGames();
</script>

<style>
    /* Enhanced Game Management UI */
    .games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }

    .game-card {
        background: 272929;
        border: 1px solid #444;
        border-radius: 12px;
        padding: 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .game-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
        border-color: #6b7280;
    }

    .game-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #444;
    }

    /* Game Header */
    .game-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px 20px 16px 20px;
        border-bottom: 1px solid #4b5563;
    }

    .game-title-section {
        flex: 1;
    }

    .game-title {
        font-size: 18px;
        font-weight: 700;
        color: #f9fafb;
        margin: 0 0 8px 0;
        line-height: 1.3;
    }

    .game-category-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #6b7280;
        color: #f9fafb;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .game-rating {
        font-size: 16px;
        color: #d1d5db;
        white-space: nowrap;
    }

    /* Game Content */
    .game-content {
        padding: 20px;
    }

    .game-info {
        margin-bottom: 16px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .info-label {
        color: #9ca3af;
        font-weight: 500;
    }

    .info-value {
        color: #f3f4f6;
        font-weight: 600;
    }

    /* Game Consoles */
    .game-consoles {
        margin-bottom: 20px;
    }

    .consoles-label {
        font-size: 12px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .consoles-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .console-tag {
        display: inline-block;
        padding: 4px 8px;
        background: #4b5563;
        color: #d1d5db;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid #6b7280;
    }

    .no-consoles {
        color: #6b7280;
        font-style: italic;
        font-size: 12px;
    }

    /* Card Actions */
    .card-actions {
        padding: 16px 20px 20px 20px;
        border-top: 1px solid #4b5563;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .card-actions .btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .card-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .btn-icon {
        font-size: 14px;
    }

    /* Console Selection */
    .console-selection {
        margin-top: 12px;
    }

    .console-selection-header {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    .console-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        max-height: 300px;
        overflow-y: auto;
        padding: 4px;
        border: 1px solid #4b5563;
        border-radius: 8px;
        background: #374151;
    }

    .console-selection-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #4b5563;
        border: 2px solid #6b7280;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .console-selection-card:hover {
        border-color: #9ca3af;
        background: #6b7280;
    }

    .console-selection-card.selected {
        border-color: #d1d5db;
        background: #6b7280;
        box-shadow: 0 0 0 3px rgba(209, 213, 219, 0.2);
    }

    .console-checkbox {
        position: relative;
    }

    .console-checkbox input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .console-checkbox label {
        display: block;
        width: 20px;
        height: 20px;
        background: #6b7280;
        border: 2px solid #9ca3af;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .console-checkbox input[type="checkbox"]:checked+label {
        background: #d1d5db;
        border-color: #d1d5db;
    }

    .console-checkbox input[type="checkbox"]:checked+label::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #374151;
        font-size: 12px;
        font-weight: bold;
    }

    .console-info {
        flex: 1;
    }

    .console-name {
        font-size: 14px;
        font-weight: 600;
        color: #f3f4f6;
        margin-bottom: 2px;
    }

    .console-status {
        font-size: 12px;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .console-status.available {
        background: #6b7280;
        color: #d1d5db;
    }

    .console-status.occupied {
        background: #6b7280;
        color: #d1d5db;
    }

    .console-status.maintenance {
        background: #6b7280;
        color: #d1d5db;
    }

    /* Modal Enhancements */
    .modal-content {
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .games-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .console-grid {
            grid-template-columns: 1fr;
        }

        .game-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }

        .card-actions {
            flex-direction: column;
        }

        .card-actions .btn {
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

    .game-card {
        animation: slideInUp 0.3s ease-out;
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>