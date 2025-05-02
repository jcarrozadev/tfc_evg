document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const dniInput = document.getElementById('dni');
    const form = dniInput.closest('form');
    const dniLetters = 'TRWAGMYFPDXBNJZSQVHLCKE';

    function showValidation(input, valid, message = '') {
        input.classList.remove('is-valid', 'is-invalid');
        let feedback = input.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.classList.add('invalid-feedback');
            input.insertAdjacentElement('afterend', feedback);
        }
        if (valid) {
            input.classList.add('is-valid');
            feedback.textContent = '';
        } else {
            input.classList.add('is-invalid');
            feedback.textContent = message;
        }
    }

    function validateName() {
        const value = nameInput.value.trim();
        showValidation(nameInput, value.length > 0, 'El nombre es obligatorio.');
    }

    function validateEmail() {
        const value = emailInput.value.trim();
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        showValidation(emailInput, regex.test(value), 'Introduce un correo válido.');
    }

    function validatePhone() {
        const value = phoneInput.value.trim();
        const isValid = /^\d{9}$/.test(value);
        showValidation(phoneInput, isValid, 'Introduce un teléfono válido de 9 dígitos.');
    }

    function validateDNI() {
        const value = dniInput.value.trim().toUpperCase();
        const dniRegex = /^(\d{8})([A-Z])$/;
        const match = value.match(dniRegex);
        if (!match) {
            showValidation(dniInput, false, 'Formato de DNI no válido (ej. 12345678Z).');
            return;
        }
        const number = parseInt(match[1], 10);
        const letter = match[2];
        const expectedLetter = dniLetters[number % 23];
        showValidation(dniInput, letter === expectedLetter, `La letra del DNI debería ser "${expectedLetter}".`);
    }

    nameInput.addEventListener('input', validateName);
    emailInput.addEventListener('input', validateEmail);
    phoneInput.addEventListener('input', validatePhone);
    dniInput.addEventListener('input', validateDNI);

    form.addEventListener('submit', function (e) {
        validateName();
        validateEmail();
        validatePhone();
        validateDNI();

        if (form.querySelectorAll('.is-invalid').length > 0) {
            e.preventDefault();
        }
    });
});
