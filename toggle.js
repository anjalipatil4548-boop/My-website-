/*
 * Sidebar Toggle - Sidebar is always visible (stable)
 * This file is kept for compatibility but sidebar is now always shown
 */

// Sidebar is always visible - no toggle needed
// Keeping function for backwards compatibility if needed
function toggleSidebar() {
    console.log('Sidebar is always visible');
}

// Init - ensure sidebar is visible
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.left-sidebar');
    if (sidebar) {
        sidebar.classList.remove('hidden');
    }
});

