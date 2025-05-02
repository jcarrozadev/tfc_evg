document.addEventListener('DOMContentLoaded', function() {
    const draggables = document.querySelectorAll('.draggable');
    const dropzones = document.querySelectorAll('.dropzone');
    const availableTeachersColumn = document.querySelector('.col-md-4');

    let draggedItem = null;
    let originalDropzone = null;

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', dragStart);
        draggable.addEventListener('dragend', dragEnd);
    });

    dropzones.forEach(dropzone => {
        dropzone.addEventListener('dragover', dragOver);
        dropzone.addEventListener('dragenter', dragEnter);
        dropzone.addEventListener('dragleave', dragLeave);
        dropzone.addEventListener('drop', handleDropWithConfirmation);

        if (dropzone.children.length === 0) {
            resetDropzoneText(dropzone);
        }
    });

    function dragStart() {
        draggedItem = this;
        originalDropzone = this.parentElement.classList.contains('dropzone') ? this.parentElement : null;
        setTimeout(() => { this.style.opacity = '0.4'; }, 0);
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

    function handleDropWithConfirmation() {
        const dropzone = this;
        dropzone.classList.remove('dropzone-hover');
    
        const absenceId = dropzone.dataset.absenceId;
        const teacherId = draggedItem.dataset.teacherId;
        const teacherName = draggedItem.querySelector('span').textContent.trim();
    
        swal({
            title: "Estas a punto de asignar una guardia",
            text: `¿Quieres asignar a ${teacherName} a esta guardia?`,
            
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#0F4C81",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, asignar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result && result.value !== undefined && result.value !== true) {
                return;
            }
        
            fetch('/admin/guards/assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    absence_id: absenceId,
                    teacher_id: teacherId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Asignado", `${teacherName} ha sido asignado.`, "success").then(() => {
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    });
                } else {
                    swal("Error", data.message || "No se pudo asignar la guardia.", "error");
                }
            })
            .catch(error => {
                swal("Error", "No se pudo asignar la guardia.", "error");
            });
        });
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
