document.getElementById('editTeacherForm').addEventListener('submit', function(event) {
    event.preventDefault();

    swal({
        title: '¿Estás seguro?',
        text: "Una vez editado, no podrás deshacer los cambios.",
        icon: 'warning',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn-cancel",
                closeModal: true
            },
            confirm: {
                text: "Sí, guardar cambios",
                value: true,
                visible: true,
                className: "btn-confirm",
                closeModal: true
            }
        }
    }).then((result) => {
        if (result) {
            let form = document.getElementById('editTeacherForm');
            let formData = new FormData(form);

            console.log(formData);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("¡Éxito!", data.success, "success")
                        .then(() => {
                            location.reload();
                        });
                } else {
                    swal("Error", data.error, "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                swal("Error", "Hubo un problema en el servidor.", "error");
            });
        }
    });
});

document.getElementById('editClassForm').addEventListener('submit', function(event) {
    event.preventDefault();

    swal({
        title: '¿Estás seguro?',
        text: "Una vez editado, no podrás deshacer los cambios.",
        icon: 'warning',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn-cancel",
                closeModal: true
            },
            confirm: {
                text: "Sí, guardar cambios",
                value: true,
                visible: true,
                className: "btn-confirm",
                closeModal: true
            }
        }
    }).then((result) => {
        if (result) {
            let form = document.getElementById('editClassForm');
            let formData = new FormData(form);

            console.log(formData);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("¡Éxito!", data.success, "success")
                        .then(() => {
                            location.reload();
                        });
                } else {
                    swal("Error", data.error, "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                swal("Error", "Hubo un problema en el servidor.", "error");
            });
        }
    });
});
