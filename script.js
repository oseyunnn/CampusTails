function openModal() { 
    document.getElementById('pawModal').style.display = 'block'; 
    document.body.style.overflow = 'hidden'; 
}

function closeModal() { 
    document.getElementById('pawModal').style.display = 'none'; 
    document.body.style.overflow = 'auto'; 
}

// Close modal when clicking background
window.onclick = function(e) { 
    if (e.target.className === 'modal-overlay') closeModal(); 
}

// FIXED: Add Location Functionality
function addLoc() {
    const loc = prompt("Enter the name of the new location:");
    
    // Check if input is valid
    if (loc && loc.trim() !== "") {
        const selectElement = document.getElementById('locSel');
        
        // Create new option
        const newOption = document.createElement('option');
        newOption.text = loc.trim();
        newOption.value = loc.trim();
        
        // Add to dropdown and select it
        selectElement.add(newOption);
        selectElement.value = loc.trim();
        
        console.log("New location added: " + loc);
    }
}

// Image Previews
function handlePreview(inputId, containerId) {
    const input = document.getElementById(inputId);
    const container = document.getElementById(containerId);
    input.onchange = function() {
        const [file] = input.files;
        if (file) {
            container.style.backgroundImage = `url(${URL.createObjectURL(file)})`;
            // Hide the icon/text inside the uploader when a photo is set
            const label = container.querySelector('label');
            if (label) label.style.opacity = '0';
        }
    }
}

handlePreview('c-in', 'c-prev');
handlePreview('p-in', 'p-prev');