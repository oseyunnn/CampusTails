document.addEventListener('DOMContentLoaded', function() {
    
    // TAB SWITCHING
    window.openTab = function(evt, tabName) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
        evt.currentTarget.classList.add('active');
    }

    // MODAL OPEN & POPULATE
    window.openModal = function() {
        document.getElementById('pawModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        const store = document.getElementById('pet-data-store');
        const vaccines = JSON.parse(store.getAttribute('data-vaccines'));
        const meds = JSON.parse(store.getAttribute('data-meds'));
        const history = JSON.parse(store.getAttribute('data-history'));

        document.getElementById('vaccine-wrap').innerHTML = '';
        document.getElementById('med-wrap').innerHTML = '';
        document.getElementById('hist-wrap').innerHTML = '';

        vaccines.forEach(v => addNewRow('vaccine-wrap', 'vaccine', v));
        meds.forEach(m => addNewRow('med-wrap', 'medication', m));
        history.forEach(h => addNewRow('hist-wrap', 'history', h));
    }

    window.closeModal = function() {
        document.getElementById('pawModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // DYNAMIC ROW GENERATION (ALL FIELDS)
    window.addNewRow = function(containerId, type, data = null) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'dynamic-item';
        const delIcon = `<i class="fas fa-trash-alt delete-icon" onclick="this.closest('.dynamic-item').remove()"></i>`;

        if (type === 'vaccine') {
            div.innerHTML = `
                <div class="form-row"><label>Vaccine ${delIcon}</label><input type="text" value="${data?.vaccine_name || ''}" placeholder="Name"></div>
                <div class="form-row"><label>Date Adm.</label><input type="date" value="${data?.date_administered || ''}"></div>
                <div class="form-row"><label>Next Due</label><input type="date" value="${data?.next_due_date || ''}"></div>
                <div class="form-row"><label>Veterinarian</label><input type="text" value="${data?.veterinarian || ''}" placeholder="Vet Name"></div>
                <div class="form-row"><label>Documents</label><label class="add-pill">Upload File<input type="file" hidden></label></div>`;
        } else if (type === 'medication') {
            div.innerHTML = `
                <div class="form-row"><label>Medicine ${delIcon}</label><input type="text" value="${data?.medicine_name || ''}" placeholder="Name"></div>
                <div class="form-row"><label>Dosage</label><input type="text" value="${data?.dosage || ''}" placeholder="5mg"></div>
                <div class="form-row"><label>Date Started</label><input type="date" value="${data?.date_started || ''}"></div>
                <div class="form-row vertical-stack"><label>Purpose</label><input type="text" value="${data?.purpose || ''}" placeholder="Purpose..."></div>`;
        } else if (type === 'history') {
            div.innerHTML = `
                <div class="form-row"><label>Illness ${delIcon}</label><input type="text" value="${data?.illness_name || ''}" placeholder="Name"></div>
                <div class="form-row"><label>Category</label><select>
                    <option ${data?.category=='Illness'?'selected':''}>Illness</option>
                    <option ${data?.category=='Surgery'?'selected':''}>Surgery</option>
                </select></div>
                <div class="form-row"><label>Date Diagnosed</label><input type="date" value="${data?.date_diagnosed || ''}"></div> 
                <div class="form-row"><label>Ongoing</label><select>
                    <option ${data?.is_ongoing?'selected':''}>Yes</option>
                    <option ${!data?.is_ongoing?'selected':''}>No</option>
                </select></div>`;
        }
        container.appendChild(div);
    }

    

    // FORM SUBMISSION (SCRAPE ALL FIELDS)
 document.getElementById('fullEditForm').onsubmit = async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const petId = formData.get('pet_id'); // Capture the UUID

    // --- SCRAPE VACCINES ---
    const vaccines = [];
    document.querySelectorAll('#vaccine-wrap .dynamic-item').forEach((item, index) => {
        const inputs = item.querySelectorAll('input');
        if (inputs[0].value) { // Only if Name is filled
            vaccines.push({
                vaccine_name: inputs[0].value,
                date_administered: inputs[1].value || null,
                next_due_date: inputs[2].value || null,
                veterinarian: inputs[3].value,
                pet_id: petId // CRITICAL: Re-connect foreign key
            });
            // Handle file upload
            if (inputs[4].files[0]) {
                formData.append(`vaccine_doc_${index}`, inputs[4].files[0]);
            }
        }
    });
    formData.append('vaccines', JSON.stringify(vaccines));

    // --- SCRAPE MEDICATIONS ---
    const medications = [];
    document.querySelectorAll('#med-wrap .dynamic-item').forEach(item => {
        const inputs = item.querySelectorAll('input');
        if (inputs[0].value) {
            medications.push({
                medicine_name: inputs[0].value,
                dosage: inputs[1].value,
                date_started: inputs[2].value || null,
                purpose: inputs[3].value,
                pet_id: petId // CRITICAL: Re-connect foreign key
            });
        }
    });
    formData.append('medications', JSON.stringify(medications));

    // --- SCRAPE HISTORY ---
    const history = [];
    document.querySelectorAll('#hist-wrap .dynamic-item').forEach(item => {
        const inputs = item.querySelectorAll('input');
        const selects = item.querySelectorAll('select');
        if (inputs[0].value) {
            history.push({
                illness_name: inputs[0].value,
                category: selects[0].value,
                date_diagnosed: inputs[1].value || null,
                is_ongoing: selects[1].value === 'Yes', // Boolean for DB
                pet_id: petId // CRITICAL: Re-connect foreign key
            });
        }
    });
    formData.append('history', JSON.stringify(history));

    try {
        const res = await fetch('update_pet.php', { method: 'POST', body: formData });
        const result = await res.json();
        
        if(result.success) { 
            alert("Profile updated successfully!"); 
            window.location.reload(); 
        } else {
            alert("Database Error: " + result.message);
        }
    } catch (err) {
        alert("Server connection failed.");
    }
}

    window.deletePet = async function() {
    const petId = document.querySelector('input[name="pet_id"]').value;
    const petName = document.querySelector('input[name="name"]').value;

    if (confirm(`Are you sure you want to delete ${petName}? This action cannot be undone.`)) {
        try {
            const res = await fetch('delete_pet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `pet_id=${petId}`
            });
            
            const result = await res.json();
            
            if (result.success) {
                alert("Pet record deleted successfully.");
                // Redirect back to the pets directory
                window.location.href = '../pets_directory/pets.php';
            } else {
                alert("Error: " + result.message);
            }
        } catch (err) {
            alert("Failed to connect to server.");
        }
    }
}

        function setupImagePreview(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        if (input && preview) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Apply the new image as background preview
                        preview.style.backgroundImage = `url('${e.target.result}')`;
                        // Optional: Add a class to indicate it's a new unsaved preview
                        preview.classList.add('preview-active');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    // Initialize previews for both Cover and Profile images
    setupImagePreview('cover-in', 'cover-prev');
    setupImagePreview('profile-in', 'profile-prev');
    
    // Modal Helpers
    window.goToStep2 = () => { document.getElementById('step1').style.display='none'; document.getElementById('step2').style.display='block'; }
    window.goToStep1 = () => { document.getElementById('step2').style.display='none'; document.getElementById('step1').style.display='block'; }
    window.addLocation = () => { let l = prompt("New Location:"); if(l) { let s = document.getElementById('locSel'); s.add(new Option(l, l)); s.value = l; } }
    
    // Redirects
    document.querySelector('.logout-btn').onclick = (e) => { e.preventDefault(); window.location.href = '../../login/login.php'; };
    document.querySelector('.logo img').onclick = () => window.location.href = '../dashboard.php';
});