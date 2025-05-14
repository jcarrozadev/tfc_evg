document.addEventListener("DOMContentLoaded", () => {
    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const dniInput = document.getElementById("dni");
    const phoneInput = document.getElementById("phone");
    const form = document.getElementById("createTeacherForm");

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

    nameInput.addEventListener("input", () => {
        if (nameInput.value.includes("@")) {
            showError(nameInput, "El nombre no puede contener '@'");
        } else {
            showSuccess(nameInput, "Nombre válido");
        }
    });

    emailInput.addEventListener("input", () => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            showError(emailInput, "Correo no válido");
        } else {
            showSuccess(emailInput, "Correo válido");
        }
    });

    dniInput.addEventListener("input", () => {
        const value = dniInput.value.toUpperCase();
        const dniRegex = /^\d{8}[A-Z]$/;
        if (!dniRegex.test(value)) {
            showError(dniInput, "Formato DNI inválido. Debe tener 8 números y una letra");
            return;
        }

        const number = parseInt(value.substring(0, 8));
        const expectedLetter = dniLetters[number % 23];
        const actualLetter = value.charAt(8);

        if (actualLetter !== expectedLetter) {
            showError(dniInput, `Letra incorrecta. Debe ser: ${expectedLetter}`);
        } else {
            showSuccess(dniInput, "DNI válido");
        }
    });

    phoneInput.addEventListener("input", () => {
        const phoneRegex = /^[679]\d{8}$/;
        if (!phoneRegex.test(phoneInput.value)) {
            showError(phoneInput, "El teléfono debe ser español (empieza por 6, 7 o 9 y tiene 9 cifras)");
        } else {
            showSuccess(phoneInput, "Teléfono válido");
        }
    });

    form.addEventListener("submit", (e) => {
        const inputs = [nameInput, emailInput, dniInput, phoneInput];
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

function generatePassword(length = 12) {
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
    let password = "";
    for (let i = 0; i < length; i++) {
        password += charset.charAt(Math.floor(Math.random() * charset.length));
    }
    document.getElementById("password").value = password;
}