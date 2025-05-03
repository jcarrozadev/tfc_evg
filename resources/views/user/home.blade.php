@extends('layout')

@section('title', 'Inicio')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
    <div class="bg-blur">
        <div class="text-center mt-4">
            @include('components.buttons.button', [
                'text' => 'Cerrar Sesión',
                'route' => 'logout',
                'class' => 'btn btn-salir'
            ])
        </div>
    
        <div class="container text-center container-center">
            <div class="row align-items-center">
                <div class="col-md-4 text-md-start text-center">
                    <h1 class="fw-bold">Bienvenid@, <br>{{ $user->name }}</h1>
                    <p class="text-muted">Bienvenido a tu panel de Profesor desde aquí podrás gestionar todo lo que necesites</p>
                </div>
                <div class="col-md-4">
                    <div class="rounded-circle overflow-hidden mx-auto profile-pic mb-3" style="width: 250px; height: 250px;">
                        <img src="{{ asset('storage/' . ($user->image_profile ?: 'avatars/default.png')) }}" 
                            alt="Foto del Profesor" 
                            class="img-fluid h-100 w-100 object-fit-cover">
                    </div>
                    <div class="hora" id="hora">--:--</div>
                </div>
                <div class="col-md-4 text-md-end text-center">
                    <h2 class="fw-bold">Guardia</h2>
                    <div class="guardia-box mt-3">
                        <p class="text-white fw-bold fs-4">¡Tienes Guardia!</p>
                        @include('components.buttons.button', [
                            'text' => 'Consultar Guardia',
                            'route' => 'admin.admin',
                            'class' => 'btn btn-guardia'
                        ])
                    </div>
                </div>
            </div>
    
            <div class="row mt-5 g-4">
                <div class="col-6 col-md-3">
                <div class="icon-card d-flex flex-column justify-content-center align-items-center" style="height: 25vh;">
                    <i class="bi bi-clock"></i>
                    <div>HORARIO</div>
                </div>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('teacher.notifyAbsence') }}" class="text-decoration-none">
                        <div class="icon-card d-flex flex-column justify-content-center align-items-center" style="height: 25vh;">
                            <i class="bi bi-bell"></i>
                            NOTIFICAR AUSENCIA
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('teacher.consultAbsence') }}" class="text-decoration-none">
                        <div class="icon-card d-flex flex-column justify-content-center align-items-center" style="height: 25vh;">
                            <i class="bi bi-paperclip"></i>
                            CONSULTAR AUSENCIA
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('teacher.settings') }}" class="text-decoration-none">
                        <div class="icon-card d-flex flex-column justify-content-center align-items-center" style="height: 25vh;">
                            <i class="bi bi-gear"></i>
                            AJUSTES
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
    @include('sweetAlerts.swal')
@endpush
