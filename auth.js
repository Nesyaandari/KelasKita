document.addEventListener('DOMContentLoaded', function () {
    const authSection = document.getElementById('authSection');
    if (!authSection) return; // kalau halaman tidak ada authSection, lewati

    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

    // Deteksi path ke root
    let pathPrefix = '';
    const depth = window.location.pathname.split('/').length - 2; 
    if (depth > 0) {
        pathPrefix = '../'.repeat(depth);
    }

    if (isLoggedIn) {
        const username = localStorage.getItem('username') || 'User';
        const avatar = localStorage.getItem('userAvatar') || `${window.location.origin}/images/addina.jpg`;

        authSection.innerHTML = `
            <div style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <span style="font-weight:600;">${username}</span>
                <img src="${avatar}" alt="User Avatar" class="avatar-img" style="width:32px; height:32px; border-radius:50%;">
            </div>
        `;
    } else {
        authSection.innerHTML = `
            <a href="${pathPrefix}signup.html" class="signup">Sign Up</a>
            <a href="${pathPrefix}login.html" class="login">Login</a>
        `;
    }
});
