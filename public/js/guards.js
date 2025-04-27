document.addEventListener('DOMContentLoaded', function() {
    const draggables = document.querySelectorAll('.draggable');
    const dropzones = document.querySelectorAll('.dropzone');
    const availableTeachersColumn = document.querySelector('.col-md-4');
    
    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', dragStart);
        draggable.addEventListener('dragend', dragEnd);
    });
    
    dropzones.forEach(dropzone => {
        dropzone.addEventListener('dragover', dragOver);
        dropzone.addEventListener('dragenter', dragEnter);
        dropzone.addEventListener('dragleave', dragLeave);
        dropzone.addEventListener('drop', dragDrop);
        
        if (dropzone.children.length === 0) {
            resetDropzoneText(dropzone);
        }
    });
    
    let draggedItem = null;
    let originalDropzone = null;
    
    function dragStart() {
        draggedItem = this;
        originalDropzone = this.parentElement.classList.contains('dropzone') ? this.parentElement : null;
        setTimeout(() => {
            this.style.opacity = '0.4';
        }, 0);
    }
    
    function dragEnd() {
        this.style.opacity = '1';
        if (originalDropzone && originalDropzone.children.length === 0) {
            resetDropzoneText(originalDropzone);
        }
    }
    
    function dragOver(e) {
        e.preventDefault();
    }
    
    function dragEnter(e) {
        e.preventDefault();
        this.classList.add('dropzone-hover');
    }
    
    function dragLeave() {
        this.classList.remove('dropzone-hover');
    }
    
    function dragDrop() {
        this.classList.remove('dropzone-hover');
        if (this.children.length > 0) {
            const existingTeacher = this.firstChild;
            if (originalDropzone) {
                originalDropzone.appendChild(existingTeacher);
            } else {
                availableTeachersColumn.appendChild(existingTeacher);
            }
        }
        if (this.textContent.trim() === 'Arrastra profesor') {
            this.innerHTML = '';
            this.classList.remove('bg-light');
            this.classList.add('bg-white');
            this.classList.add('text-muted');
        }
        this.appendChild(draggedItem);
    }
    
    availableTeachersColumn.addEventListener('dragover', function(e) {
        e.preventDefault();
    });
    
    availableTeachersColumn.addEventListener('drop', function(e) {
        e.preventDefault();
        if (draggedItem) {
            this.appendChild(draggedItem);
            if (originalDropzone && originalDropzone.children.length === 0) {
                resetDropzoneText(originalDropzone);
            }
        }
    });
    
    function resetDropzoneText(dropzone) {
        dropzone.innerHTML = 'Arrastra profesor';
        dropzone.classList.add('bg-light');
        dropzone.classList.remove('bg-white');
        dropzone.classList.add('text-muted');
    }
});
