@extends('layout')

@section('title', 'Profesorado | Inicio')

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
                        <img src="{{ $user->image_profile !== 'default.png' ? asset('storage/' . $user->image_profile) : asset('img/default.png') }}" 
                            alt="Foto del Profesor" 
                            class="img-fluid h-100 w-100 object-fit-cover">
                    </div>
                    <div class="hora" id="hora">--:--</div>
                </div>
                <div class="col-md-4 text-md-end text-center">
                    <h2 class="fw-bold">
                        Tus <br>
                        Guardias
                    </h2>
                    @if(isset($guard) && $guard)
                        <a href="{{ route('teacher.personalGuard') }}" class="text-decoration-none">
                            <div class="guardia-box mt-3">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <p class="text-white fw-bold fs-4 text-center">¡Tienes Guardia!</p>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="guardia-box mt-3">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <p class="text-white fw-bold fs-4 text-center">No tienes Guardia</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    
            <div class="row mt-5 row-cols-2 row-cols-md-5 g-4">
                <div class="col">
                    <a href="{{ route('teacher.personalSchedule') }}" class="text-decoration-none">
                        <div class="icon-card d-flex flex-column justify-content-center align-items-center" style="height: 25vh;">
                            <i class="bi bi-clock"></i>
                            <div>HORARIO</div>
                        </div>
                    </a>
                </div>
                
                <div class="col">
                    <a href="{{ route('teacher.notifyAbsence') }}" class="text-decoration-none">
                        <div class="icon-card d-flex flex-column justify-content-center align-items-center" style="height: 25vh;">
                            <i class="bi bi-bell"></i>
                            NOTIFICAR AUSENCIA
                        </div>
                    </a>
                </div>
                
                <div class="col">
                    <a href="{{ route('teacher.consultAbsence') }}" class="text-decoration-none">
                        <div class="icon-card d-flex flex-column justify-content-center align-items-center" style="height: 25vh;">
                            <i class="bi bi-paperclip"></i>
                            CONSULTAR AUSENCIA
                        </div>
                    </a>
                </div>
                
                <div class="col">
                    <a href="{{ route('teacher.guardsToday') }}" class="text-decoration-none">
                        <div class="icon-card d-flex flex-column justify-content-center align-items-center" style="height: 25vh;">
                            <i class="bi bi-calendar-check"></i>
                            GUARDIAS DEL DÍA
                        </div>
                    </a>
                </div>
                
                <div class="col">
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
