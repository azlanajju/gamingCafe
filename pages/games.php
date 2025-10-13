<?php
$pageTitle = 'Game Management';
$currentPage = 'games';
require_once __DIR__ . '/../includes/header.php';
?>

<section id="games" class="content-section active">
    <div class="section-header">
        <h2 class="section-title">Game Management</h2>
        <button class="btn btn--primary" id="add-game-btn">Add New Game</button>
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
                        card.innerHTML = `
                        <h3>${game.name}</h3>
                        <p><strong>Category:</strong> ${game.category}</p>
                        <p><strong>Developer:</strong> ${game.developer}</p>
                        <p><strong>Rating:</strong> ${game.rating || 'N/A'}</p>
                        <div class="card-actions">
                            <button class="btn btn--sm btn--primary" onclick="editGame(${game.id})">Edit</button>
                            <button class="btn btn--sm btn--danger" onclick="deleteGame(${game.id})">Delete</button>
                        </div>
                    `;
                        grid.appendChild(card);
                    });
                }
            });
    }

    // Add game button
    document.getElementById('add-game-btn').addEventListener('click', () => {
        document.getElementById('game-modal-title').textContent = 'Add New Game';
        document.getElementById('game-form').reset();
        document.getElementById('game-id').value = '';
        document.getElementById('add-game-modal').classList.remove('hidden');
    });

    // Cancel
    document.getElementById('cancel-game').addEventListener('click', () => {
        document.getElementById('add-game-modal').classList.add('hidden');
    });

    // Form submit
    document.getElementById('game-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const id = document.getElementById('game-id').value;
        const data = {
            name: document.getElementById('game-name').value,
            category: document.getElementById('game-category').value,
            developer: document.getElementById('game-developer').value,
            rating: document.getElementById('game-rating').value || null,
            release_date: document.getElementById('game-release').value || null,
            branch_id: 1
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

    // Initial load
    loadGames();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

