<div class="modal fade" id="createClassModal" tabindex="-1" aria-labelledby="createClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createClassForm" action="{{ route('class.create') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClassModalLabel">Añadir Clase</h5>
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
        </form>
    </div>
</div>