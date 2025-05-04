document.addEventListener('DOMContentLoaded', () => {
    const guardarBtn = document.getElementById('btn-confirmar');
    const form = document.getElementById('form-guardias');

    guardarBtn.addEventListener('click', function (e) {
        e.preventDefault();

        const selectsProfesores = document.querySelectorAll('select[name*="[user_id]"]');
        if (selectsProfesores.length === 0) {
            console.warn('⚠ No se han encontrado selects de profesores.');
            return;
        }

        let vacios = 0;
        let rellenos = 0;

        selectsProfesores.forEach(select => {
            const valor = select.value?.trim();
            if (!valor || valor === '-' || valor === '') {
                vacios++;
                select.classList.add('is-invalid', 'is-warning');
            } else {
                rellenos++;
                select.classList.remove('is-invalid', 'is-warning');
            }
        });

        if (rellenos === 0) {
            swal({
                title: "Formulario vacío",
                text: "No has asignado ningún profesor. No se puede guardar nada.",
                icon: "info",
                button: "Aceptar"
            });
            return;
        }

        if (vacios > 0) {
            swal({
                title: "Huecos incompletos",
                text: `Hay ${vacios} profesores sin asignar. ¿Guardar igualmente?`,
                icon: "warning",
                buttons: {
                    cancel: "Cancelar",
                    confirm: {
                        text: "Sí, guardar",
                        value: true,
                        closeModal: false
                    }
                }
            }).then((confirmado) => {
                if (confirmado) {
                    form.requestSubmit();
                }
            });
        } else {
            swal({
                title: "¿Guardar guardias?",
                text: "Todos los huecos tienen profesor asignado. ¿Deseas guardar?",
                icon: "info",
                buttons: {
                    cancel: "Cancelar",
                    confirm: {
                        text: "Sí, guardar",
                        value: true,
                        closeModal: false
                    }
                }
            }).then((confirmado) => {
                if (confirmado) {
                    form.requestSubmit();
                }
            });
        }
    });

    document.getElementById('reset').addEventListener('click', function (e) {
        e.preventDefault();
        swal({
            title: "¿Restablecer guardias?",
            text: "¿Estás seguro de que deseas restablecer todas las guardias?",
            icon: "warning",
            buttons: {
                cancel: "Cancelar",
                confirm: {
                    text: "Sí, resetear",
                    value: true,
                    closeModal: true
                }
            }
        }).then((confirmado) => {
            if (confirmado) {
                const selects = document.querySelectorAll('select');
                selects.forEach(select => {
                    select.value = '-';
                    select.classList.remove('is-invalid', 'is-warning');
                });
                swal({
                    title: "Guardias restablecidas",
                    text: "Todas las guardias han sido restablecidas.",
                    icon: "success",
                    timer: 2000,
                    buttons: false
                });
            }
        });
    });
});
