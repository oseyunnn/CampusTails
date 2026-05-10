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

// --- SAVING LOGIC ---//

document.getElementById('petForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // 1. Initialize FormData (handles basic info like Name, Species automatically)
    const formData = new FormData(this);

    // 2. Manually gather Health Records from dynamic rows
   const vaccines = [];
    const vaccineRows = document.querySelectorAll('#vaccine-wrap .dynamic-item');
    
    vaccineRows.forEach((item, index) => {
        const inputs = item.querySelectorAll('input');
        
        // Push text data to the array
        vaccines.push({
            vaccine_name: inputs[0].value,
            date_administered: inputs[1].value,
            next_due_date: inputs[2].value,
            veterinarian: inputs[3].value
        });

        // Capture the PDF file if selected (index 4 is the Upload File input)
        const pdfFile = inputs[4].files[0];
        if (pdfFile) {
            formData.append(`vaccine_doc_${index}`, pdfFile);
        }
    });

    const medications = [];
    document.querySelectorAll('#med-wrap .dynamic-item').forEach(item => {
        const inputs = item.querySelectorAll('input');
        medications.push({
            medicine_name: inputs[0].value,
            dosage: inputs[1].value,
            date_started: inputs[2].value,
            purpose: inputs[3].value
        });
    });

        const medical_history = [];
        // Find every history item added
        const historyItems = document.querySelectorAll('#hist-wrap .dynamic-item');

        historyItems.forEach(item => {
            // Get specific elements within THIS item
            const nameInp = item.querySelector('input[placeholder="Name"]');
            const dateInp = item.querySelector('input[type="date"]');
            const categorySel = item.querySelectorAll('select')[0]; // The Category dropdown
            const ongoingSel = item.querySelectorAll('select')[1];  // The Ongoing dropdown

            // Only add if there is a name typed in
            if (nameInp && nameInp.value.trim() !== "") {
                medical_history.push({
                    illness_name: nameInp.value,
                    category: categorySel.value,
                    date_diagnosed: dateInp.value,
                    // Convert "Yes" to true, "No" to false for Supabase
                    is_ongoing: (ongoingSel.value === "Yes")
                });
            }
        });

    console.log("History Data Scraped:", medical_history);

    
    formData.append('vaccines', JSON.stringify(vaccines));
    formData.append('medications', JSON.stringify(medications));
    formData.append('history', JSON.stringify(medical_history));

    // 3. Attach Image Files explicitly from your custom upload triggers
    const profileFile = document.getElementById('profile-in').files[0];
    const coverFile = document.getElementById('cover-in').files[0];
    if (profileFile) formData.append('profile_img_file', profileFile);
    if (coverFile) formData.append('cover_img_file', coverFile);

    try {
        const response = await fetch('save_pet.php', {
            method: 'POST',
            body: formData // DO NOT add Content-Type header here
        });

        const result = await response.json();
        if (result.success) {
            alert("PawFriend Saved Successfully!");
            window.location.reload();
        } else {
            alert("Error: " + result.message);
            console.log("Debug Info:", result.debug);
        }
    } catch (err) {
        console.error("Fetch Error:", err);
        alert("Server communication error.");
    }
});