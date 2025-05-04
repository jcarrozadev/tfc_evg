document.addEventListener('DOMContentLoaded', function() {
    const draggables = document.querySelectorAll('.draggable');
    const dropzones = document.querySelectorAll('.dropzone');
    const availableTeachersColumn = document.querySelector('.col-md-4');

    let draggedItem = null;
    let originalDropzone = null;
    let pendingAssignments = [];

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', function () {
            draggedItem = this;
            originalDropzone = this.parentElement.classList.contains('dropzone') ? this.parentElement : null;
            setTimeout(() => { this.style.opacity = '0.4'; }, 0);
        });

        draggable.addEventListener('dragend', function () {
            this.style.opacity = '1';
            if (originalDropzone && originalDropzone.children.length === 0) {
                resetDropzoneText(originalDropzone);
            }
        });
    });

    dropzones.forEach(dropzone => {
        dropzone.addEventListener('dragover', e => e.preventDefault());
        dropzone.addEventListener('dragenter', e => dropzone.classList.add('dropzone-hover'));
        dropzone.addEventListener('dragleave', e => dropzone.classList.remove('dropzone-hover'));

        dropzone.addEventListener('drop', function () {
            dropzone.classList.remove('dropzone-hover');
            if (!draggedItem) return;

            const absenceSessions = JSON.parse(dropzone.dataset.sessionIds);
            const teacherSessions = JSON.parse(draggedItem.dataset.sessions);
            const absenceSessionIds = absenceSessions.map(s => parseInt(s));
            const canAssign = teacherSessions.some(ts => absenceSessionIds.includes(parseInt(ts.session_id)));

            if (!canAssign) {
                swal("No se puede asignar", "El profesor no está disponible para esta sesión.", "error");
                return;
            }

            const existingTeacher = dropzone.querySelector('.draggable');
            if (existingTeacher) {
                availableTeachersColumn.appendChild(existingTeacher);
            }

            dropzone.innerHTML = '';
            dropzone.appendChild(draggedItem);
            dropzone.classList.remove('bg-light', 'text-muted');
            dropzone.classList.add('bg-white');

            const absenceId = dropzone.dataset.absenceId;
            const teacherId = draggedItem.dataset.teacherId;
            const existing = pendingAssignments.find(a => a.absence_id === absenceId);
            if (existing) {
                existing.teacher_id = teacherId;
            } else {
                pendingAssignments.push({ absence_id: absenceId, teacher_id: teacherId });
            }
        });
    });

    document.querySelectorAll('.dropzone').forEach(zone => {
        const sessionId = parseInt(zone.dataset.sessionId);
        zone.style.borderLeftColor = sessionColors[sessionId] || 'transparent';
    });

    document.querySelectorAll('.draggable').forEach(card => {
        const sessions = JSON.parse(card.dataset.sessions);
        if (sessions.length > 0) {
            const firstSession = sessions[0];
            const color = sessionColors[firstSession.session_id] || 'transparent';
            card.style.borderLeftColor = color;
        }
    });

    availableTeachersColumn.addEventListener('dragover', e => e.preventDefault());
    availableTeachersColumn.addEventListener('drop', function () {
        if (!draggedItem) return;
        this.appendChild(draggedItem);
        if (originalDropzone && originalDropzone.classList.contains('dropzone')) {
            originalDropzone.innerHTML = '<span class="text-muted">Arrastra profesor</span>';
            originalDropzone.classList.add('bg-light');
            originalDropzone.classList.remove('bg-white');
            const absenceId = originalDropzone.dataset.absenceId;
            pendingAssignments = pendingAssignments.filter(a => a.absence_id != absenceId);
        }
    });

    document.getElementById('saveAssignmentsBtn').addEventListener('click', function () {
        const dropzones = document.querySelectorAll('.dropzone');
        const incomplete = Array.from(dropzones).some(dropzone => !dropzone.querySelector('.draggable'));

        if (incomplete) {
            swal({
                title: "Algunas ausencias no tienen profesor asignado.",
                text: "¿Guardar igualmente?",
                icon: "warning",
                buttons: {
                    cancel: "Cancelar",
                    confirm: {
                        text: "Sí, guardar",
                        value: true,
                        closeModal: false
                    }
                }
            }).then((willSave) => {
                if (willSave) {
                    submitAssignments();
                }
            });
        } else {
            swal({
                title: "¿Guardar asignaciones?",
                text: "Todas las ausencias están cubiertas. ¿Quieres guardar?",
                icon: "info",
                buttons: {
                    cancel: "Cancelar",
                    confirm: {
                        text: "Sí, guardar",
                        value: true,
                        closeModal: false
                    }
                }
            }).then((willSave) => {
                if (willSave) {
                    submitAssignments();
                }
            });
        }
    });

    function submitAssignments() {
        if (pendingAssignments.length === 0) {
            swal("Nada que guardar", "No hay cambios pendientes.", "info");
            return;
        }

        fetch('/admin/guards/assign', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },            
            body: JSON.stringify({ assignments: pendingAssignments })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const saved = data.saved.length;
                const skipped = data.skipped.length;
                let message = `${saved} guardias asignadas correctamente.`;
        
                if (skipped > 0) {
                    message += `\n${skipped} no se asignaron porque no tenían profesor.`;
                }
        
                swal("Resumen de guardias", message, "success").then(() => location.reload());
            } else {
                swal("Error", data.message || "No se pudo guardar.", "error");
            }
        })
        .catch(() => {
            swal("Error", "Hubo un problema con la petición.", "error");
        });
        
    }

    function resetDropzoneText(dropzone) {
        dropzone.innerHTML = '';
        dropzone.textContent = 'Arrastra profesor';
        dropzone.classList.add('bg-light');
        dropzone.classList.remove('bg-white');
        dropzone.classList.add('text-muted');
    }
});