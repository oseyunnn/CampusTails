// MODAL CONTROLS
function openModal() {
    document.getElementById('pawModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    resetToStep1();
}

function closeModal() {
    document.getElementById('pawModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.onclick = function(e) {
    if (e.target.className === 'modal-overlay') closeModal();
}

// STEP NAVIGATION
function goToStep2() {
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
    document.querySelector('.modal-content-scrollable').scrollTop = 0;
}

function goToStep1() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
    document.querySelector('.modal-content-scrollable').scrollTop = 0;
}

function resetToStep1() {
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';
    document.getElementById('vaccine-wrap').innerHTML = '';
    document.getElementById('med-wrap').innerHTML = '';
    document.getElementById('hist-wrap').innerHTML = '';
    addNewRow('vaccine-wrap', 'vaccine');
    addNewRow('med-wrap', 'medication');
    addNewRow('hist-wrap', 'history');
}

// DYNAMIC ROWS
function addNewRow(containerId, type) {
    const container = document.getElementById(containerId);
    const div = document.createElement('div');
    div.className = 'dynamic-item';

    const delIcon = `<i class="fas fa-trash-alt delete-icon" onclick="this.closest('.dynamic-item').remove()"></i>`;

    if (type === 'vaccine') {
        div.innerHTML = `
            <div class="form-row"><div class="label-group"><label>Vaccine Name</label>${delIcon}</div><input type="text" placeholder="Name"></div>
            <div class="form-row"><label>Date Administered</label><input type="date"></div>
            <div class="form-row"><label>Next Due Date</label><input type="date"></div>
            <div class="form-row"><label>Veterinarian</label><input type="text" placeholder="Vet Name"></div>
            <div class="form-row"><label>Documents</label><label class="add-pill">Upload File<input type="file" hidden></label></div>`;
    } else if (type === 'medication') {
        div.innerHTML = `
            <div class="form-row"><div class="label-group"><label>Medicine Name</label>${delIcon}</div><input type="text" placeholder="Name"></div>
            <div class="form-row"><label>Dosage</label><input type="text" placeholder="e.g. 5mg"></div>
            <div class="form-row"><label>Date Started</label><input type="date"></div>
            <div class="form-row vertical-stack"><label>Purpose</label><input type="text" placeholder="Describe the purpose of this medication..."></div>`;
    } else if (type === 'history') {
        div.innerHTML = `
            <div class="form-row"><div class="label-group"><label>Illness Name</label>${delIcon}</div><input type="text" placeholder="Name"></div>
            <div class="form-row"><label>Category</label><select><option>Surgery</option><option>Illness</option><option>Injury</option></select></div>
            <div class="form-row"><label>Date Diagnosed</label><input type="date"></div> 
          <div class="form-row"><label>Ongoing</label><select><option>Yes</option><option>No</option></select></div>`;
    }
    container.appendChild(div);
}

// HELPERS
function addLocation() {
    const loc = prompt("Add new location:");
    if (loc && loc.trim() !== "") {
        const sel = document.getElementById('locSel');
        const opt = document.createElement('option');
        opt.text = loc; opt.value = loc;
        sel.add(opt); sel.value = loc;
    }
}

function setupPreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const prev = document.getElementById(previewId);
    input.onchange = () => {
        const [file] = input.files;
        if (file) {
            prev.style.backgroundImage = `url(${URL.createObjectURL(file)})`;
            const trig = prev.querySelector('.upload-trigger');
            if (trig) trig.style.opacity = '0';
        }
    }
}
setupPreview('cover-in', 'cover-prev');
setupPreview('profile-in', 'profile-prev');

document.addEventListener('DOMContentLoaded', function() {
    
    // --- LOGIN PAGE PASSWORD TOGGLE ---
    const eyeBtn = document.getElementById('login_eye_toggle_btn');
    const passField = document.getElementById('login_secret_input');

    if (eyeBtn && passField) {
        eyeBtn.addEventListener('click', function() {
            // Force the switch between "password" (bullets) and "text" (readable)
            if (passField.type === "password") {
                passField.type = "text";
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                passField.type = "password";
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }

            // Visual 'pop' feedback on the icon
            this.style.transition = '0.15s ease';
            this.style.transform = 'translateY(-50%) scale(1.3)';
            setTimeout(() => { this.style.transform = 'translateY(-50%) scale(1)'; }, 150);
        });
    }

    // --- BUTTON INTERACTIVE FEEDBACK ---
    const loginActionBtn = document.querySelector('.auth-login-button');
    if (loginActionBtn) {
        loginActionBtn.addEventListener('mousedown', () => loginActionBtn.style.transform = "scale(0.96)");
        loginActionBtn.addEventListener('mouseup', () => loginActionBtn.style.transform = "translateY(-2px) scale(1.02)");
    }
});
// --- LOGOUT FUNCTIONALITY ---
document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.querySelector('.logout-btn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the "#" jumping the page
            
            // Add a small fade-out effect for professionalism (Optional)
            document.body.style.transition = "opacity 0.5s";
            document.body.style.opacity = "0";

            // Redirect to login page after a brief moment
            setTimeout(() => {
                window.location.href = '../login/index.php'; 
            }, 300);
        });
    }
});