document.addEventListener('DOMContentLoaded', function() {
    
    // EYE TOGGLE FUNCTIONALITY
    const eyeBtn = document.getElementById('eyeBtn');
    const passInput = document.getElementById('passInput');

    if (eyeBtn && passInput) {
        eyeBtn.addEventListener('click', function() {
            // Check current type and flip it
            if (passInput.type === "password") {
                passInput.type = "text";
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                passInput.type = "password";
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }

            // Professional interaction effect
            this.style.transition = '0.2s';
            this.style.transform = 'translateY(-50%) scale(1.3)';
            setTimeout(() => {
                this.style.transform = 'translateY(-50%) scale(1)';
            }, 150);
        });
    }

    // BUTTON HOVER FEEDBACK
    const loginBtn = document.querySelector('.submit-btn');
    if (loginBtn) {
        loginBtn.addEventListener('mousedown', () => loginBtn.style.transform = "scale(0.96)");
        loginBtn.addEventListener('mouseup', () => loginBtn.style.transform = "scale(1.03)");
    }
});