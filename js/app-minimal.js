// Minimal JavaScript for Gaming Cafe PHP Pages
// This replaces the full app.js for PHP pages

// Global variables
let currentUser = null;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing minimal Gaming Cafe App');
    
    // Initialize theme toggle
    initThemeToggle();
    
    // Initialize any global functionality
    initGlobalFeatures();
    
    console.log('Minimal app initialization complete');
});

// Theme toggle functionality
function initThemeToggle() {
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.classList.toggle('dark-mode', savedTheme === 'dark');
        updateThemeButton(savedTheme);
        
        themeToggle.addEventListener('click', function() {
            const isDark = document.body.classList.contains('dark-mode');
            const newTheme = isDark ? 'light' : 'dark';
            
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('theme', newTheme);
            updateThemeButton(newTheme);
        });
    }
}

// Update theme button icon
function updateThemeButton(theme) {
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.textContent = theme === 'dark' ? '☀️' : '🌙';
    }
}

// Initialize global features
function initGlobalFeatures() {
    // Add any global features that are needed across PHP pages
    console.log('Global features initialized');
}

// Utility functions
function showToast(message, type = 'success') {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
        color: white;
        border-radius: 4px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Add CSS for toast animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style);

// Export functions for use in other scripts
window.GameCafeMinimal = {
    showToast: showToast,
    initThemeToggle: initThemeToggle
};

