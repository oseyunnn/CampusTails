document.addEventListener('DOMContentLoaded', function() {
    // Logo redirect uniformity
    const logo = document.querySelector('.logo img');
    if(logo) {
        logo.addEventListener('click', () => { window.location.href = 'home.php'; });
    }

    // Scroll reveal for navbar
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('.nav-container');
        if (window.scrollY > 100) {
            nav.style.background = 'rgba(123, 97, 255, 0.9)'; // Primary Purple
        } else {
            nav.style.background = 'rgba(255,255,255,0.15)';
        }
    });
});