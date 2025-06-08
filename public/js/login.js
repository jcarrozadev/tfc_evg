document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const loginBtn = document.getElementById('login');
    const registerBtn = document.getElementById('register');

    if (loginBtn && registerBtn && container) {
        loginBtn.addEventListener('click', () => container.classList.remove('active'));
        registerBtn.addEventListener('click', () => container.classList.add('active'));

        if (window.innerWidth <= 768) {
            container.classList.remove('active');
        }
    }

    const patterns = {
        name: /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,}$/,
        email: /^[a-zA-Z0-9._%+-]+@fundacionloyola\.es$/,
        password: /^.{8,}$/,
        phone: /^\d{9}$/,
        dni: /^[0-9]{8}[A-Za-z]$/
    };

    const inputs = document.querySelectorAll('input');

    inputs.forEach(input => {
        const wrapper = input.closest('.input-wrapper');
        if (!wrapper) return;

        let icon = wrapper.querySelector('.validation-icon');
        if (!icon) {
            icon = document.createElement('span');
            icon.classList.add('validation-icon');
            wrapper.appendChild(icon);
        }

        input.addEventListener('input', () => {
            const type = input.name;
            const value = input.value.trim();

            if (patterns[type] && patterns[type].test(value)) {
                wrapper.classList.add('valid');
                wrapper.classList.remove('invalid');
                if (patterns[type] && patterns[type].test(value)) {
                    wrapper.classList.add('valid');
                    wrapper.classList.remove('invalid');
                    icon.innerHTML = '<i class="fa-solid fa-check"></i>';
                } else {
                    wrapper.classList.remove('valid');
                    wrapper.classList.add('invalid');
                    icon.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                }

            } else {
                wrapper.classList.remove('valid');
                wrapper.classList.add('invalid');
                if (patterns[type] && patterns[type].test(value)) {
                    wrapper.classList.add('valid');
                    wrapper.classList.remove('invalid');
                    icon.innerHTML = '<i class="fa-solid fa-check"></i>';
                } else {
                    wrapper.classList.remove('valid');
                    wrapper.classList.add('invalid');
                    icon.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                }
            }
        });
    });

    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const input = toggle.closest('.input-wrapper').querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                toggle.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                toggle.innerHTML = '<i class="fa-solid fa-eye"></i>';
            }
        });
    });
});
