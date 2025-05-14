$(document).ready(function() {
    $('#datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        autoclose: true,
        todayHighlight: true,
        clearBtn: true,
        orientation: "bottom auto",
        container: '#datepicker-container',
        startDate: new Date() 
    });

    $('#datepicker-container .input-group-text').on('click', function() {
        $('#datepicker').datepicker('show');
    });

    const justificanteInput = document.getElementById('justify');
    const filenameLabel = document.getElementById('filename-label');

    justificanteInput.addEventListener('change', function () {
        const file = justificanteInput.files[0];
        if (file) {
            filenameLabel.textContent = file.name;
        } else {
            filenameLabel.textContent = '';
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const validTextPattern = /^[A-Za-z0-9ÁÉÍÓÚáéíóúÜüÑñ&()\s¿?¡!.,:;'"-]+$/;
    
    const tipoAusenciaSelect = document.getElementById('tipo_ausencia');
    const descripcionTextarea = document.getElementById('descripcion');
    const justificanteInput = document.getElementById('justificante');

    function validateTextField(field, errorMessage = 'Este campo no puede estar vacío ni contener caracteres no permitidos.') {
        const value = field.value.trim();
        const parent = field.closest('div');
        let errorDiv = parent.querySelector('.error-message');

        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.classList.add('error-message', 'text-danger', 'mt-1', 'small', 'd-none');
            parent.appendChild(errorDiv);
        }

        if (validTextPattern.test(value) && value.length > 0) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            errorDiv.classList.add('d-none');
            errorDiv.textContent = '';
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            errorDiv.classList.remove('d-none');
            errorDiv.textContent = errorMessage;
        }
    }

    function validateSelect(selectField) {
        const value = selectField.value;
        const parent = selectField.closest('div');
        let errorDiv = parent.querySelector('.error-message');

        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.classList.add('error-message', 'text-danger', 'mt-1', 'small', 'd-none');
            parent.appendChild(errorDiv);
        }

        if (value !== '') {
            selectField.classList.remove('is-invalid');
            selectField.classList.add('is-valid');
            errorDiv.classList.add('d-none');
            errorDiv.textContent = '';
        } else {
            selectField.classList.remove('is-valid');
            selectField.classList.add('is-invalid');
            errorDiv.classList.remove('d-none');
            errorDiv.textContent = 'Debe seleccionar un tipo de ausencia.';
        }
    }

    function validateFileInput(input) {
        const parent = input.closest('div');
        let errorDiv = parent.querySelector('.error-message');

        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.classList.add('error-message', 'text-danger', 'mt-1', 'small', 'd-none');
            parent.appendChild(errorDiv);
        }

        if (input.files.length > 0) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            errorDiv.classList.add('d-none');
            errorDiv.textContent = '';
            return true;
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            errorDiv.classList.remove('d-none');
            errorDiv.textContent = 'El justificante es obligatorio.';
            return false;
        }
    }

    descripcionTextarea.addEventListener('input', function () {
        validateTextField(descripcionTextarea, 'La descripción no puede estar vacía ni contener caracteres no permitidos.');
    });

    tipoAusenciaSelect.addEventListener('change', function () {
        validateSelect(tipoAusenciaSelect);
    });

    justificanteInput.addEventListener('change', function () {
        validateFileInput(justificanteInput);
    });

    const form = document.querySelector('form');
    form.addEventListener('submit', function (e) {
        validateSelect(tipoAusenciaSelect);
        validateTextField(descripcionTextarea, 'La descripción no puede estar vacía ni contener caracteres no permitidos.');
        validateFileInput(justificanteInput);

        const invalidFields = form.querySelectorAll('.is-invalid');
        if (invalidFields.length > 0) {
            e.preventDefault();
        }
    });
    
});


$(document).ready(function () {
    const datepicker = $('#datepicker');    

    datepicker.datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        autoclose: true,
        todayHighlight: true,
        clearBtn: true,
        orientation: "bottom auto",
        container: '#datepicker-container',
    });

    $('#datepicker-container .input-group-text').on('click', function () {
        $('#datepicker').datepicker('show');
    });

    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    const btnToday = document.getElementById('btn-today');
    const btnTomorrow = document.getElementById('btn-tomorrow');

    if (btnToday && btnTomorrow) {
        btnToday.addEventListener('click', function () {
            const today = new Date();
            const formatted = formatDate(today);
            datepicker.datepicker('update', formatted);
        });

        btnTomorrow.addEventListener('click', function () {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const formatted = formatDate(tomorrow);
            datepicker.datepicker('update', formatted);
        });
    }

});
