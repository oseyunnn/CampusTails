document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Logo Redirect
    const logo = document.querySelector('.logo img');
    if(logo) {
        logo.onclick = () => window.location.href = '../dashboard.php';
    }

    // 2. Logout functionality
    const logoutBtn = document.querySelector('.logout-btn');
    if(logoutBtn) {
        logoutBtn.onclick = (e) => {
            e.preventDefault();
            if(confirm("Are you sure you want to logout?")) {
                window.location.href = '../../login/login.php';
            }
        };
    }

    // 3. Stagger card entrance animation (Optional but looks pro)
    const cards = document.querySelectorAll('.vaccine-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // 4. Sort Button logic
    const sortBtn = document.querySelector('.sort-btn');
    if(sortBtn) {
        sortBtn.onclick = () => {
            // You can implement a toggle or dropdown here
            console.log("Sort clicked");
        };
    }
});