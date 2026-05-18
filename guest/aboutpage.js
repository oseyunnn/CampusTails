document.addEventListener('DOMContentLoaded', function() {
    // 1. Navbar Scroll Effect (Same as Home)
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('.nav-container');
        if (window.scrollY > 100) {
            nav.style.background = 'rgba(123, 97, 255, 0.9)'; 
        } else {
            nav.style.background = 'rgba(255,255,255,0.15)';
        }
    });

    // 2. Simple Scroll Reveal for Cards
    const observerOptions = { threshold: 0.1 };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
            }
        });
    }, observerOptions);

    const cards = document.querySelectorAll('.crew-card');
    cards.forEach(card => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        card.style.transition = "all 0.6s ease-out";
        observer.observe(card);
    });
});