<form id="editClassForm" method="POST">
    @csrf
    @method('PUT')

    <div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel">Editar Clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="num_class" class="form-label">Número de Clase</label>
                        <input type="number" class="form-control" id="num_class" name="num_class" required>
                        <div class="invalid-feedback">Debe ser un número del 1 al 9.</div>
                    </div>
                    <div class="mb-3">
                        <label for="course" class="form-label">Curso</label>
                        <input type="text" class="form-control" id="course" name="course" required>
                        <div class="invalid-feedback">Máximo 4 palabras, solo letras.</div>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Letra</label>
                        <input type="text" class="form-control" id="code" value="-" name="code" required>
                        <div class="invalid-feedback">Debe contener una letra o "-" si vacío.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>

</form>