document.addEventListener("DOMContentLoaded", () => {
    const editForm = document.getElementById("editTeacherForm");
    const editName = document.getElementById("editName");
    const editEmail = document.getElementById("editEmail");
    const editPhone = document.getElementById("editPhone");
    const editDNI = document.getElementById("editDNI");

    const dniLetters = "TRWAGMYFPDXBNJZSQVHLCKE";

    function showError(input, message) {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        setFeedback(input, message, "invalid");
    }

    function showSuccess(input, message = "") {
        input.classList.remove("is-invalid");
        input.classList.add("is-valid");
        setFeedback(input, message, "valid");
    }

    function setFeedback(input, message, type) {
        const className = type === "valid" ? "valid-feedback" : "invalid-feedback";
        const oppositeClass = type === "valid" ? "invalid-feedback" : "valid-feedback";

        if (input.nextElementSibling && input.nextElementSibling.classList.contains(oppositeClass)) {
            input.nextElementSibling.remove();
        }

        if (!input.nextElementSibling || !input.nextElementSibling.classList.contains(className)) {
            const feedback = document.createElement("div");
            feedback.className = className;
            feedback.innerText = message;
            input.parentNode.appendChild(feedback);
        } else {
            input.nextElementSibling.innerText = message;
        }
    }

    editName.addEventListener("input", () => {
        if (editName.value.includes("@")) {
            showError(editName, "El nombre no puede contener '@'");
        } else {
            showSuccess(editName, "Nombre válido");
        }
    });

    editEmail.addEventListener("input", () => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(editEmail.value)) {
            showError(editEmail, "Correo no válido");
        } else {
            showSuccess(editEmail, "Correo válido");
        }
    });

    editPhone.addEventListener("input", () => {
        const phoneRegex = /^[679]\d{8}$/;
        if (!phoneRegex.test(editPhone.value)) {
            showError(editPhone, "El teléfono debe empezar por 6, 7 o 9 y tener 9 dígitos");
        } else {
            showSuccess(editPhone, "Teléfono válido");
        }
    });

    editDNI.addEventListener("input", () => {
        const value = editDNI.value.toUpperCase();
        const dniRegex = /^\d{8}[A-Z]$/;
        if (!dniRegex.test(value)) {
            showError(editDNI, "Formato DNI inválido. Debe tener 8 números y una letra");
            return;
        }

        const number = parseInt(value.substring(0, 8));
        const expectedLetter = dniLetters[number % 23];
        const actualLetter = value.charAt(8);

        if (actualLetter !== expectedLetter) {
            showError(editDNI, `Letra incorrecta. Debe ser: ${expectedLetter}`);
        } else {
            showSuccess(editDNI, "DNI válido");
        }
    });

    editForm.addEventListener("submit", (e) => {
        const inputs = [editName, editEmail, editPhone, editDNI];
        let hasError = false;

        inputs.forEach(input => {
            input.dispatchEvent(new Event('input'));
            if (input.classList.contains("is-invalid")) {
                hasError = true;
            }
        });

        if (hasError) {
            e.preventDefault();
        }
    });
});

function fillEditModal(data) {
    document.getElementById('editId').value = data.id;
    document.getElementById('editName').value = data.name;
    document.getElementById('editEmail').value = data.email;
    document.getElementById('editPhone').value = data.phone;
    document.getElementById('editDNI').value = data.dni;

    document.getElementById('editTeacherForm').action = `/teacher/${data.id}`;
}
