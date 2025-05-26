document.addEventListener('DOMContentLoaded', () => {
    const guardarBtn = document.getElementById('btn-confirmar');
    const form = document.getElementById('form-guardias');

    const initSelect2 = () => {
        $('.profesor-select, select[name*="[class_id]"]').select2({
            width: '100%',
            placeholder: "--",
            allowClear: true,
            language: {
                noResults: () => "No se encontraron resultados",
                searching: () => "Buscando...",
                inputTooShort: () => "Escribe al menos un carácter"
            }
        });
    };

    initSelect2();

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
            const container = $(select).next('.select2-container');

            if (!valor || valor === '-' || valor === '') {
                vacios++;
                select.classList.add('is-invalid');
                container.addClass('select2-warning');
            } else {
                rellenos++;
                select.classList.remove('is-invalid');
                container.removeClass('select2-warning');
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
            }).then(confirmado => {
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
            }).then(confirmado => {
                if (confirmado) {
                    form.requestSubmit();
                }
            });
        }
    });

    document.getElementById('reset').addEventListener('click', function (e) {
        e.preventDefault();

        swal({
            title: "¿Restablecer libro de guardias?",
            text: "¿Estás seguro de que deseas restablecer las guardias?",
            icon: "warning",
            buttons: {
                cancel: "Cancelar",
                completo: {
                    text: "Sí, completo",
                    value: "completo",
                    closeModal: true
                },
                clases: {
                    text: "Sí, clases",
                    value: "clases",
                    closeModal: true
                }
            }
        }).then((option) => {
            if (!option) return;

            let route = '';
            const bodyData = { _method: 'DELETE' };

            if (option === 'completo') {
                route = routeReset;
            } else if (option === 'clases') {
                route = routeResetClases;
            }

            fetch(route, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(bodyData)
            })
            .then(response => {
                if (!response.ok) throw new Error('Error al restablecer guardias');
                return response.json();
            })
            .then(data => {
                const selects = document.querySelectorAll('select');

                if (route === routeReset) {
                    selects.forEach(select => {
                        select.value = '-';
                        select.classList.remove('is-invalid');
                        $(select).next('.select2-container').removeClass('select2-warning');
                    });
                }

                if (route === routeResetClases) {
                    selects.forEach(select => {
                        if (select.name.includes('[class_id]')) {
                            select.value = '-';
                            select.classList.remove('is-invalid');
                            $(select).next('.select2-container').removeClass('select2-warning');
                        }
                    });
                }

                $('.profesor-select, select[name*="[class_id]"]').val(null).trigger('change');
                initSelect2();

                swal({
                    title: "Guardias restablecidas",
                    text: data.message,
                    icon: "success",
                    timer: 2000,
                    buttons: false
                });
            })
            .catch(error => {
                swal("Error", error.message, "error");
            });
        });
    });
});
