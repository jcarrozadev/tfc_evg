document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    const updateMobileMode = () => {
        if (window.innerWidth < 768) {
            container.classList.add("mobile");
        } else {
            container.classList.remove("mobile");
        }
    };

    updateMobileMode(); // Al cargar
    window.addEventListener("resize", updateMobileMode);

    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
});