@extends('layout')

@section('title', 'Panel Admin')

@section('content')
    @include('templates.navBar')

    <div class="container-md p-4 rounded shadow-sm bg-light" style="max-width: 90%;">
        <div class="text-center mb-4">
            <h1 class="fw-bold">
                ¡Te damos la bienvenida, {{ Auth::check() ? Auth::user()->name : '' }}!
            </h1>
        </div>

        <div class="bg-white p-4 rounded shadow-sm mb-5">
            <h4 class="fw-semibold mb-4"><i class="fas fa-chart-line"></i> Gráfico de Actividad Semanal</h4>
            <canvas id="activityChart" height="100"></canvas>
        </div>

        <div class="row mb-5">
            <h4 class="mb-3 fw-semibold"><i class="fas fa-chart-pie"></i> Estadísticas del Sistema</h4>

            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Profesores Registrados</h5>
                        <p class="display-6">{{ $profesoresCount }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success mb-3 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Guardias Asignadas Hoy</h5>
                        <p class="display-6">{{ $guardiasHoy }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Cursos Activos</h5>
                        <p class="display-6">{{ $clasesCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts') 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.guardiasSemanales = @json($guardiasSemanales);
    </script>
    <script src="{{ asset('js/adminPanel.js') }}"></script>
@endpush
