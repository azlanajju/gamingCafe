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
                        card.setAttribute('data-category', game.category.toLowerCase());
                        let actionButtons = '';
                        if (USER_ROLE === 'Super Admin' || USER_ROLE === 'Admin' || USER_ROLE === 'Manager') {
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
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: 16px;
        padding: 0;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .game-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: var(--shadow-lg);
        border-color: var(--color-primary);
    }


    .game-card::after {
        content: '🎮';
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 24px;
        opacity: 0.1;
        transition: all 0.3s ease;
    }

    .game-card:hover::after {
        opacity: 0.3;
        transform: rotate(15deg) scale(1.2);
    }

    /* Enhanced Game Header */
    .game-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 24px 24px 20px 24px;
        border-bottom: 1px solid var(--color-border);
        background: linear-gradient(135deg, var(--color-bg-1) 0%, var(--color-bg-2) 100%);
        position: relative;
    }

    .game-title-section {
        flex: 1;
        z-index: 2;
    }

    .game-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--color-text);
        margin: 0 0 12px 0;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .game-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: var(--color-primary);
        color: var(--color-white);
        border-radius: 25px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: var(--shadow-xs);
        position: relative;
    }

    .game-category-badge::before {
        content: '🏷️';
        font-size: 14px;
    }

    .game-rating {
        font-size: 18px;
        color: var(--color-warning);
        white-space: nowrap;
        background: var(--color-surface);
        padding: 8px 12px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: var(--shadow-xs);
        z-index: 2;
    }

    /* Enhanced Game Content */
    .game-content {
        padding: 24px;
        background: var(--color-surface);
    }

    .game-info {
        margin-bottom: 20px;
        background: var(--color-bg-1);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-size: 14px;
        padding: 8px 0;
        border-bottom: 1px solid var(--color-border);
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .info-label {
        color: var(--color-text-secondary);
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-label::before {
        content: '👨‍💻';
        font-size: 16px;
    }

    .info-item:nth-child(2) .info-label::before {
        content: '📅';
    }

    .info-value {
        color: var(--color-text);
        font-weight: 600;
        background: var(--color-surface);
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid var(--color-border);
    }

    /* Enhanced Game Consoles */
    .game-consoles {
        margin-bottom: 20px;
        background: var(--color-bg-2);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
    }

    .consoles-label {
        font-size: 14px;
        font-weight: 700;
        color: var(--color-text);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .consoles-label::before {
        content: '🎮';
        font-size: 16px;
    }

    .consoles-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .console-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        background: var(--color-surface);
        color: var(--color-text);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid var(--color-border);
        box-shadow: var(--shadow-xs);
        transition: all 0.2s ease;
    }

    .console-tag:hover {
        background: var(--color-primary);
        color: var(--color-white);
        transform: translateY(-1px);
    }

    .console-tag::before {
        content: '🖥️';
        font-size: 14px;
    }

    .no-consoles {
        color: var(--color-text-secondary);
        font-style: italic;
        font-size: 14px;
        padding: 12px;
        text-align: center;
        background: var(--color-surface);
        border-radius: 8px;
        border: 1px dashed var(--color-border);
    }

    /* Enhanced Card Actions */
    .card-actions {
        padding: 20px 24px 24px 24px;
        border-top: 1px solid var(--color-border);
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        background: var(--color-bg-1);
    }

    .card-actions .btn {
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

    .card-actions .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .card-actions .btn:hover::before {
        left: 100%;
    }

    .card-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .card-actions .btn--primary:hover {
        background: var(--color-primary-hover);
    }

    .card-actions .btn--danger:hover {
        background: var(--color-error);
        transform: translateY(-2px) scale(1.05);
    }

    .btn-icon {
        font-size: 16px;
        transition: transform 0.2s ease;
    }

    .card-actions .btn:hover .btn-icon {
        transform: scale(1.2);
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


    /* Enhanced Animations */
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

    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }

        100% {
            background-position: calc(200px + 100%) 0;
        }
    }

    .game-card {
        animation: slideInUp 0.4s ease-out;
    }

    .game-card:nth-child(even) {
        animation-delay: 0.1s;
    }

    .game-card:nth-child(3n) {
        animation-delay: 0.2s;
    }

    /* Loading shimmer effect */
    .game-card.loading {
        background: linear-gradient(90deg, var(--color-surface) 25%, var(--color-bg-1) 50%, var(--color-surface) 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    /* Hover glow effect */
    .game-card:hover {
        box-shadow: var(--shadow-lg), 0 0 20px rgba(var(--color-primary-rgb, 59, 130, 246), 0.15);
    }

    /* Category-specific styling */
    .game-card[data-category="fps"] .game-category-badge {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .game-card[data-category="sports"] .game-category-badge {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .game-card[data-category="racing"] .game-category-badge {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .game-card[data-category="rpg"] .game-category-badge {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .game-card[data-category="action"] .game-category-badge {
        background: linear-gradient(135deg, #f97316, #ea580c);
    }

    .game-card[data-category="strategy"] .game-category-badge {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>