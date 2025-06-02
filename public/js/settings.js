document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const dniInput = document.getElementById('dni');
    const form = dniInput.closest('form');
    const dniLetters = 'TRWAGMYFPDXBNJZSQVHLCKE';
    const apiKeyInput = document.getElementById('callmebot_apikey');
    const copyBtn = document.getElementById('copyMessageBtn');

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
        const regex = /^[^\s@]+@fundacionloyola\.es$/i;
        showValidation(emailInput, regex.test(value), 'El correo debe ser @fundacionloyola.es');
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

    apiKeyInput.addEventListener('mouseenter', function () {
        const tooltip = document.createElement('div');
        tooltip.id = 'whatsapp-tooltip';
        tooltip.innerHTML = 'Para obtener el código, envía a este Whatsapp +34 644 23 47 85:<br> El mensaje que puedes copiar en el botón de la izquierda. El bot te responderá con tu Cod Personal.<br> En ese whatsapp recibiras las guardias que debes de cubrir';
        
        tooltip.style.position = 'absolute';
        tooltip.style.backgroundColor = '#f8f9fa';
        tooltip.style.color = '#000';
        tooltip.style.border = '1px solid #ced4da';
        tooltip.style.padding = '10px';
        tooltip.style.borderRadius = '6px';
        tooltip.style.boxShadow = '0 0 10px rgba(0,0,0,0.1)';
        tooltip.style.zIndex = 1000;
        tooltip.style.maxWidth = '400px';
        tooltip.style.fontSize = '14px';

        const rect = apiKeyInput.getBoundingClientRect();
        tooltip.style.top = `${rect.bottom + window.scrollY + 5}px`;
        tooltip.style.left = `${rect.left + window.scrollX}px`;

        document.body.appendChild(tooltip);
    });

    apiKeyInput.addEventListener('mouseleave', function () {
        const tooltip = document.getElementById('whatsapp-tooltip');
        if (tooltip) tooltip.remove();
    });

    copyBtn.addEventListener('click', function () {
        const message = 'i allow callmebot to send me messages';

        navigator.clipboard.writeText(message).then(() => {
            copyBtn.innerHTML = '<i class="fas fa-check"></i>';
            copyBtn.classList.remove('btn-outline-primary');
            copyBtn.classList.add('btn-success');

            setTimeout(() => {
                copyBtn.innerHTML = '<i class="fas fa-copy"></i>';
                copyBtn.classList.remove('btn-success');
                copyBtn.classList.add('btn-outline-primary');
            }, 2000);
        }).catch(err => {
            alert('No se pudo copiar el mensaje. Intenta manualmente.');
        });
    });
});

document.getElementById('avatarInput').addEventListener('change', function() {
    document.getElementById('submitAvatarForm').click();
});