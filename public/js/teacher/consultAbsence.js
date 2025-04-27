$(document).ready(function() {
    $('#datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        autoclose: true,
        todayHighlight: true,
        clearBtn: true,
        orientation: "bottom auto",
        container: '#datepicker-container',
    });

    $('#datepicker-container .input-group-text').on('click', function() {
        $('#datepicker').datepicker('show');
    });
});
