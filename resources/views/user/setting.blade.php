@extends('layout')

@section('title', 'Ajustes del Profesor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endpush

@section('content')
    <div class="container py-5">

        <h2 class="mb-5 fw-bold text-primary text-center">Ajustes del Profesor</h2>

        <div class="row justify-content-center align-items-center mt-custom">
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <div class="rounded-circle overflow-hidden mx-auto profile-pic mb-3">
                    <img src="ruta-de-la-imagen.png" alt="Foto del Profesor" class="img-fluid">
                </div>
                <button class="btn btn-primary">Subir Imagen</button>
            </div>

            <div class="col-12 d-md-none my-4">
                <hr>
            </div>
            <div class="col-md-1 d-none d-md-flex justify-content-center">
                <div style="width: 2px; background-color: var(--primary-color-light); height: 100%;"></div>
            </div>

            <div class="col-md-6">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" class="form-control" placeholder="Nombre">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" class="form-control" placeholder="Correo Electrónico">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Teléfono</label>
                        <input type="text" class="form-control" placeholder="Teléfono">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">DNI</label>
                        <input type="text" class="form-control" placeholder="DNI">
                    </div>

                    <div class="d-grid mb-4">
                        <button type="button" class="btn btn-outline-primary">Cambiar Contraseña</button>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary">Volver</button>
                        <button type="submit" class="btn btn-primary">Modificar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
