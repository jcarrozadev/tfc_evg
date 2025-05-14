document.addEventListener('DOMContentLoaded', () => {
    const forms = [
        {
            selector: '#createClassForm',
            validateOnStart: false
        },
        {
            selector: '#editClassForm',
            validateOnStart: false
        }
    ];

    forms.forEach(({ selector, validateOnStart }) => {
        const form = document.querySelector(selector);
        const numClass = form.querySelector('[name="num_class"]');
        const course = form.querySelector('[name="course"]');
        const code = form.querySelector('[name="code"]');
        const submitBtn = form.querySelector('button[type="submit"]');

        let touched = {
            numClass: validateOnStart,
            course: validateOnStart,
            code: validateOnStart
        };

        function validateNumClass() {
            const value = numClass.value.trim();
            const isValid = /^[1-9]$/.test(value);
            toggleValidation(numClass, isValid, touched.numClass);
            return isValid;
        }

        function validateCourse() {
            const value = course.value.trim();
            const words = value.split(/\s+/);
            const isValid = (
                words.length <= 4 &&
                words.every(word => /^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/.test(word) && word.length <= 4)
            );
            toggleValidation(course, isValid, touched.course);
            return isValid;
        }

        function validateCode() {
            const value = code.value.trim();
            const isValid = value === '-' || /^[a-zA-Z]$/.test(value);
            toggleValidation(code, isValid, touched.code);
            return isValid;
        }

        function toggleValidation(input, isValid, shouldShow) {
            if (!shouldShow) return;

            if (isValid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }
        }

        function validateForm() {
            const validNum = validateNumClass();
            const validCourse = validateCourse();
            const validCode = validateCode();
            submitBtn.disabled = !(validNum && validCourse && validCode);
        }

        function setupInput(input, name, validateFunc) {
            input.addEventListener('input', () => {
                touched[name] = true;
                validateForm();
            });
        }

        setupInput(numClass, 'numClass', validateNumClass);
        setupInput(course, 'course', validateCourse);
        setupInput(code, 'code', validateCode);

        if (validateOnStart) validateForm();
        else submitBtn.disabled = true;
    });
});