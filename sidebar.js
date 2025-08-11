// === Sidebar Logic ===
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

// Klik avatar untuk buka sidebar
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('avatar-img')) {
        sidebar.classList.add('open');
        sidebarOverlay.classList.add('show');
    }
});

// Klik overlay untuk tutup sidebar
sidebarOverlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    sidebarOverlay.classList.remove('show');
});

// Hitung prefix path (untuk halaman di subfolder)
function getPathPrefix() {
    const depth = window.location.pathname.split('/').length - 2;
    return depth > 0 ? '../'.repeat(depth) : '';
}

// Menu Profile
document.getElementById('menuProfile').addEventListener('click', () => {
    window.location.href = `${getPathPrefix()}Profile/profile.html`;
});

// Menu Settings
document.getElementById('menuSettings').addEventListener('click', () => {
    alert('Settings masih statis.');
});

// Menu Logout
document.getElementById('menuLogout').addEventListener('click', () => {
    localStorage.clear();
    window.location.href = `${getPathPrefix()}beranda.html`;
});
