document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#dataTable');

    if (!table) {
        console.error('No se encontró la tabla con ID #dataTable.');
        return;
    }

    // Inicializar DataTable
    const dataTable = new DataTable(table, {
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        paging: true,
        searching: true,
        ordering: false,
    });

    document.querySelectorAll('.column-filter').forEach(input => {
        const columnIndex = input.getAttribute('data-column');
        
        input.addEventListener('keyup', function() {
            dataTable.column(columnIndex).search(this.value).draw();
        });
    });
});