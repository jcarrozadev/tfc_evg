document.addEventListener('DOMContentLoaded', () => {
    const guardarBtn = document.getElementById('btn-confirmar');
    const form = document.querySelector('form');

    guardarBtn.addEventListener('click', function (e) {
        e.preventDefault();

        const selectsProfesores = document.querySelectorAll('select[name*="[profesor]"]');
        if (selectsProfesores.length === 0) {
            console.warn('⚠ No se han encontrado selects. ¿Estás seguro de que el DOM ya ha cargado?');
            return;
        }

        let vacios = 0;

        selectsProfesores.forEach(select => {
            const valor = select.value?.trim();
            if (!valor || valor === '-' || valor === '') {
                vacios++;
                select.classList.add('is-invalid', 'is-warning');
            } else {
                select.classList.remove('is-invalid', 'is-warning');
            }
        });

        if (vacios > 0) {
            swal({
                title: "Huecos incompletos",
                text: `Hay ${vacios} profesores sin rellenar. ¿Guardar igualmente?`,
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
                    form.submit();
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
                    form.submit();
                }
            });
        }
    });

    document.getElementById('reset').addEventListener('click', function (e) {
        e.preventDefault();
        swal({
            title: "¿Resetear guardias?",
            text: "¿Estás seguro de que deseas resetear todas las guardias?",
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
                    title: "Guardias reseteadas",
                    text: "Todas las guardias han sido reseteadas.",
                    icon: "success",
                    timer: 2000,
                    buttons: false
                });
            }
        });
    });
});