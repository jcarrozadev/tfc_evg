document.addEventListener('DOMContentLoaded', function () {
    const draggables = document.querySelectorAll('.draggable');
    const dropzones = document.querySelectorAll('.dropzone');
    const availableTeachersColumn = document.querySelector('.col-md-4');

    let draggedItem = null;
    let originalDropzone = null;
    let pendingAssignments = [];
    let pendingRemovals = [];

    const teacherMap = {};

    dropzones.forEach(dropzone => {
        const teacherCard = dropzone.querySelector('.draggable');
        if (teacherCard) {
            const absenceId = dropzone.dataset.absenceId;
            const sessionId = dropzone.dataset.sessionId;
            const teacherId = teacherCard.dataset.teacherId;

            const key = `${teacherId}_${sessionId}`;
            teacherMap[key] = true;

            const alreadyIn = pendingAssignments.find(a =>
                a.absence_id === absenceId && a.session_id === sessionId
            );
            if (!alreadyIn) {
                pendingAssignments.push({ absence_id: absenceId, session_id: sessionId, teacher_id: teacherId });
            }

            availableTeachersColumn.querySelectorAll('.draggable').forEach(card => {
                const cardTeacherId = card.dataset.teacherId;
                const cardSessions = JSON.parse(card.dataset.sessions || '[]');
                const mismaSesion = cardSessions.some(s => parseInt(s.session_id) === parseInt(sessionId));
                if (cardTeacherId === teacherId && mismaSesion) {
                    card.remove();
                }
            });
        }
    });

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
        dropzone.addEventListener('dragenter', () => dropzone.classList.add('dropzone-hover'));
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dropzone-hover'));

        dropzone.addEventListener('drop', function () {
            dropzone.classList.remove('dropzone-hover');
            if (!draggedItem) return;

            const sessionId = dropzone.dataset.sessionId;
            const rawSessions = draggedItem.dataset.sessions;
            if (!rawSessions) return swal("Error", "Este profesor no tiene sesiones disponibles.", "error");

            let teacherSessions = [];
            try { teacherSessions = JSON.parse(rawSessions); } catch (e) {}

            const canAssign = teacherSessions.some(ts => parseInt(ts.session_id) === parseInt(sessionId));
            if (!canAssign) {
                return swal("No se puede asignar", "El profesor no está disponible para esta sesión.", "error");
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
            const key = `${teacherId}_${sessionId}`;
            teacherMap[key] = true;

            const existing = pendingAssignments.find(a =>
                a.absence_id === absenceId && a.session_id === sessionId
            );
            if (existing) {
                existing.teacher_id = teacherId;
            } else {
                pendingAssignments.push({ absence_id: absenceId, session_id: sessionId, teacher_id: teacherId });
            }

            availableTeachersColumn.querySelectorAll('.draggable').forEach(card => {
                const cardTeacherId = card.dataset.teacherId;
                const raw = card.dataset.sessions || '[]';
                let sesiones = [];
                try { sesiones = JSON.parse(raw); } catch (e) {}

                const mismaSesion = sesiones.some(s => parseInt(s.session_id) === parseInt(sessionId));
                if (cardTeacherId === teacherId && mismaSesion) {
                    card.remove();
                }
            });
        });

        const sessionId = parseInt(dropzone.dataset.sessionId);
        dropzone.style.borderLeftColor = sessionColors[sessionId] || 'transparent';
    });

    document.querySelectorAll('.draggable').forEach(card => {
        const raw = card.dataset.sessions;
        if (!raw) return;

        try {
            const sessions = JSON.parse(raw);
            if (sessions.length > 0) {
                const firstSession = sessions[0];
                const color = sessionColors[firstSession.session_id] || 'transparent';
                card.style.borderLeftColor = color;
            }
        } catch (e) {}
    });

    availableTeachersColumn.addEventListener('dragover', e => e.preventDefault());
    availableTeachersColumn.addEventListener('drop', function () {
        if (!draggedItem) return;

        const sessionId = originalDropzone?.dataset.sessionId;
        const absenceId = originalDropzone?.dataset.absenceId;
        const teacherId = draggedItem.dataset.teacherId;
        const key = `${teacherId}_${sessionId}`;

        delete teacherMap[key];

        const yaExiste = Array.from(availableTeachersColumn.children).some(c => {
            const sesiones = JSON.parse(c.dataset.sessions || '[]');
            return c.dataset.teacherId === teacherId &&
                   sesiones.some(s => parseInt(s.session_id) === parseInt(sessionId));
        });

        if (!yaExiste) {
            this.appendChild(draggedItem);
        }

        if (originalDropzone && originalDropzone.classList.contains('dropzone')) {
            resetDropzoneText(originalDropzone);
            pendingAssignments = pendingAssignments.filter(a =>
                !(a.absence_id === absenceId && a.session_id === sessionId)
            );
            pendingRemovals.push({ absence_id: absenceId, session_id: sessionId });
        }
    });

    document.getElementById('saveAssignmentsBtn').addEventListener('click', function () {
        const dropzones = document.querySelectorAll('.dropzone');
        const incomplete = Array.from(dropzones).some(dropzone => !dropzone.querySelector('.draggable'));
    
        const confirmSave = () => {
            if (pendingAssignments.length === 0 && pendingRemovals.length === 0) {
                swal("Nada que guardar", "No hay cambios pendientes.", "info");
                return;
            }
    
            fetch('/admin/guards/assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    assignments: pendingAssignments,
                    removals: pendingRemovals
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const saved = data.saved.length;
                    const deleted = data.deleted.length;
                    const skipped = data.skipped.length;
    
                    let message = ` ${saved} guardias asignadas\n`;
                    if (deleted > 0) message += ` ${deleted} guardias eliminadas\n`;
                    if (skipped > 0) message += ` ${skipped} omitidas por conflicto`;
    
                    swal("Resumen de cambios", message, "success").then(() => location.reload());
                } else {
                    swal("Error", data.message || "No se pudo guardar.", "error");
                }
            })
            .catch(() => {
                swal("Error", "Hubo un problema con la petición.", "error");
            });
        };
    
        if (incomplete) {
            swal({
                title: "Algunas ausencias no tienen profesor asignado.",
                text: "¿Guardar igualmente los cambios?",
                icon: "warning",
                buttons: {
                    cancel: "Cancelar",
                    confirm: {
                        text: "Sí, guardar",
                        value: true,
                        closeModal: false
                    }
                }
            }).then(willSave => { if (willSave) confirmSave(); });
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
            }).then(willSave => { if (willSave) confirmSave(); });
        }
    });

    document.getElementById('sendEmailsBtn').addEventListener('click', function () {
        if (pendingAssignments.length === 0) {
            return swal("Nada que enviar", "No hay asignaciones de guardias para enviar correos.", "info");
        }

        fetch('/admin/guards/send-emails', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                assignments: pendingAssignments
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                swal("Éxito", data.message || "Correos enviados correctamente.", "success");
            } else {
                swal("Error", data.message || "No se pudieron enviar los correos.", "error");
            }
        })
        .catch(() => {
            swal("Error", "Hubo un problema con la petición.", "error");
        });
    });

    

    function resetDropzoneText(dropzone) {
        dropzone.innerHTML = '';
        dropzone.textContent = 'Arrastra profesor';
        dropzone.classList.add('bg-light', 'text-muted');
        dropzone.classList.remove('bg-white');
    }
});