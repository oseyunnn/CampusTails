document.addEventListener('DOMContentLoaded', function() {
    
    // TAB SWITCHING
    window.switchSegment = function(type) {
        document.querySelectorAll('.info-segment').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        
        document.getElementById('seg-' + type).classList.add('active');
        document.getElementById('tab-btn-' + type).classList.add('active');
    };

    // TOGGLE EDIT MODE
    window.toggleEdit = function(isEditing) {
        const body = document.getElementById('page-body');
        if (isEditing) {
            body.classList.remove('view-mode');
            body.classList.add('edit-mode');
        } else {
            body.classList.remove('edit-mode');
            body.classList.add('view-mode');
        }
    };

    // FORM SAVE LOGIC (Placeholder for now)
    const form = document.getElementById('updatePetForm');
    if(form) {
        form.onsubmit = function(e) {
            e.preventDefault();
            alert("Changes saved to Database!");
            toggleEdit(false);
        };
    }
});