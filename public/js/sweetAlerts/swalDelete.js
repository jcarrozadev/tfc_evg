function deleteItem(url, label = 'este registro') {
    swal({
        title: "¿Estás seguro?",
        text: `Estás a punto de eliminar ${label}.`,
        icon: "warning",
        buttons: {
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn-cancel",
                closeModal: true
            },
            confirm: {
                text: "Sí, continuar",
                value: true,
                visible: true,
                className: "btn-confirm",
                closeModal: true
            }
        }
    }).then((firstConfirm) => {
        if (firstConfirm) {
            swal({
                title: "¡Confirmación final!",
                text: `¿Realmente deseas eliminar ${label}? Esta acción no se puede deshacer.`,
                icon: "error",
                buttons: {
                    cancel: {
                        text: "No, cancelar",
                        value: null,
                        visible: true,
                        className: "btn-cancel",
                        closeModal: true
                    },
                    confirm: {
                        text: "Sí, eliminar",
                        value: true,
                        visible: true,
                        className: "btn-danger",
                        closeModal: true
                    }
                }
            }).then((secondConfirm) => {
                if (secondConfirm) {
                    fetch(url, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error("Error del servidor");
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            swal("¡Eliminado!", data.success, "success")
                                .then(() => location.reload());
                        } else {
                            swal("Error", data.error ?? "Error desconocido", "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        swal("Error", "Hubo un problema en el servidor.", "error");
                    });
                }
            });
        }
    });
}