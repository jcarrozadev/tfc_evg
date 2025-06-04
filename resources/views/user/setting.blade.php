@extends('layout')

@section('title', 'Profesorado | Ajustes del Profesor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/settings.js') }}"></script>
@endpush

@section('content')
    <div class="container py-5">

        <h2 class="mb-5 fw-bold text-primary text-center">Ajustes del Profesor</h2>

        <div class="row justify-content-center align-items-center mt-custom">
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <div class="rounded-circle overflow-hidden mx-auto profile-pic mb-3" style="width: 300px; height: 300px;">
                    <img src="{{ $user->image_profile ? asset( $user->image_profile) : asset('img/default.png') }}" 
                        class="img-fluid h-100 w-100 object-fit-cover">
                </div>

                <form action="{{ route('teacher.uploadAvatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar" class="d-none" id="avatarInput" accept="image/*" required>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('avatarInput').click()">Subir Imagen</button>
                    <button type="submit" class="d-none" id="submitAvatarForm"></button>
                </form>
            </div>

            <div class="col-12 d-md-none my-4">
                <hr>
            </div>
            <div class="col-md-1 d-none d-md-flex justify-content-center">
                <div style="width: 2px; background-color: var(--primary-color-light); height: 100%;"></div>
            </div>

            <div class="col-md-6">
                <form action="{{ route('teacher.updateSettings') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ $user->name ?? '' }}" class="form-control" placeholder="Nombre">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" class="form-control" placeholder="Correo Electrónico">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Teléfono</label>
                        <input type="text" id="phone" name="phone" value="{{ $user->phone ?? '' }}" class="form-control" placeholder="Teléfono">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">DNI</label>
                        <input type="text" id="dni" name="dni" value="{{ $user->dni ?? '' }}" class="form-control" placeholder="DNI">
                    </div>

                    <div class="input-group mb-4">
                        <button class="btn btn-outline-primary" type="button" id="copyMessageBtn" title="Copiar mensaje de activación">
                            <i class="fas fa-copy"></i>
                        </button>
                        <input type="text" id="callmebot_apikey" name="callmebot_apikey"
                            value="{{ $user->callmebot_apikey ?? '' }}"
                            class="form-control"
                            placeholder="Cod Whatsapp">
                    </div>

                    <div class="d-grid mb-4">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal" 
                            @if($user->google_id) disabled @endif>
                            Cambiar Contraseña
                        </button>                        
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.home') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@include('components.modals.changePassword')