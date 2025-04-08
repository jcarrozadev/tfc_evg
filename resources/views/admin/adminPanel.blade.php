@extends('layout')

@section('title', 'Panel Admin')

@section('content')
    @include('templates.navBar')

    <div class="container-md p-4 rounded shadow-sm bg-light">
        <div class="text-center mb-4">
            <h1 class="fw-bold">HOLA ELIA</h1>
        </div>

        <!-- Sección Estadísticas -->
        <div class="row mb-5">
            <h4 class="mb-3 fw-semibold">📊 Estadísticas del Sistema</h4>

            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Profesores Registrados</h5>
                        <p class="display-6">42</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success mb-3 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Guardias Asignadas Hoy</h5>
                        <p class="display-6">17</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Cursos Activos</h5>
                        <p class="display-6">8</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Incidencias del Día</h5>
                        <p class="display-6">3</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Información General -->
        <div class="bg-light p-5 rounded">
            <h4 class="fw-semibold mb-4">ℹ️ Información General</h4>
            <ul class="list-unstyled">
                <li class="mb-3"><strong>📅 Horarios:</strong> Los horarios de las guardias están actualizados automáticamente cada semana.</li>
                <li class="mb-3"><strong>📌 Avisos:</strong> Revisa los avisos internos desde la pestaña "Guardias".</li>
                <li class="mb-3"><strong>🔄 Cambios:</strong> Puedes solicitar intercambios de guardia directamente desde la sección "Libro Guardias".</li>
                <li class="mb-3"><strong>👨‍🏫 Profesores:</strong> Los perfiles están sincronizados con el sistema académico.</li>
            </ul>
        </div>
    </div>
@endsection
