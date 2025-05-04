<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="changePasswordForm" action="{{ route('teacher.updatePassword') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña <span>*</span></label>
                        <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="new-password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña <span>*</span></label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Cambiar</button>
                </div>
            </div>
        </form>        
    </div>
</div>