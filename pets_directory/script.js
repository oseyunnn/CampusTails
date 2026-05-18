document.addEventListener('DOMContentLoaded', function() {
    // Redirect Logo to Dashboard
    const logoImg = document.querySelector('.logo img');
    if(logoImg) {
        logoImg.addEventListener('click', () => { window.location.href = '../dashboard.php'; });
    }

    // Uniform Logout
    const logoutBtn = document.querySelector('.logout-btn');
    if(logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = '../../login/index.php';
        });
    }
});