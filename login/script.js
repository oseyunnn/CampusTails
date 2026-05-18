document.addEventListener('DOMContentLoaded', function() {
    
    // EYE TOGGLE FUNCTIONALITY
    const eyeBtn = document.getElementById('eyeBtn'); 
    const passInput = document.getElementById('passInput'); 

    if (eyeBtn && passInput) {
        eyeBtn.addEventListener('click', function() {
            if (passInput.type === "password") {
                passInput.type = "text"; 
                this.classList.remove('fa-eye-slash'); 
                this.classList.add('fa-eye'); 
            } else {
                passInput.type = "password"; 
                this.classList.remove('fa-eye'); 
                this.classList.add('fa-eye-slash');
            }

            // Clean click transformation reset
            this.style.transform = 'translateY(-50%) scale(1.15)'; 
            setTimeout(() => {
                this.style.transform = 'translateY(-50%) scale(1)'; 
            }, 100);
        });
    }

    // BUTTON CLICK INTERACTION 
    const loginBtn = document.querySelector('.submit-btn'); 
    if (loginBtn) {
        loginBtn.addEventListener('mousedown', () => {
            loginBtn.style.transform = "scale(0.97)";
        });
        loginBtn.addEventListener('mouseup', () => {
            loginBtn.style.transform = "scale(1)";
        });
        loginBtn.addEventListener('mouseleave', () => {
            loginBtn.style.transform = "scale(1)";
        });
    }
});