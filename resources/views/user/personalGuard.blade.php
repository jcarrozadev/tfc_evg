@extends('layout')

@section('title', 'Profesorado | Mis Guardias de Hoy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/guardsToday.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-5 fw-bold fs-2">
            <i class="fas fa-user-shield me-2"></i>Guardias Personales - {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </h1>

        @if(count($guards) === 0)
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>No tienes guardias asignadas para hoy.
            </div>
        @else
            <div class="row g-4">
                @foreach($guards as $guard)
                    @php
                        $sessionHour   = \Carbon\Carbon::parse($guard['hour'] ?? '00:00:00')->format('H:i');
                        $updatedAtHour = \Carbon\Carbon::parse($guard['created_at'] ?? now())->format('H:i');

                        $textGuard = $guard['text_guard'] ?? '';
                    @endphp

                    <div class="col-md-12 p-2">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-custom text-white d-flex justify-content-between align-items-center">
                                <span><i class="far fa-clock me-2"></i>{{ $sessionHour }}</span>
                                <span class="badge bg-light text-dark"><i class="far fa-calendar-check me-1"></i>{{ $updatedAtHour }}</span>
                            </div>

                            <div class="card-body d-flex align-items-start">
                                <img 
                                    src="{{ $guard['absent_teacher_image'] ? asset( $guard['absent_teacher_image']) : asset('img/default.png') }}" 
                                    alt="Foto del profesor ausente" 
                                    class="me-3 rounded-circle" 
                                    style="width:48px; height:48px; object-fit:cover;"
                                >
                                <div class="flex-grow-1">
                                    <p class="mb-1">
                                        <i class="fas fa-chalkboard-teacher me-2 text-card"></i>
                                        <strong>Profesor Ausente:</strong> {{ $guard['absent_teacher_name'] }}
                                    </p>

                                    <p class="mb-1">
                                        <i class="fas fa-school me-2 text-card"></i>
                                        <strong>Clase:</strong> {{ $guard['class_name']  ?? 'Pregunta a Elia' }}
                                    </p>

                                    <p class="mb-1">
                                        <i class="fas fa-info-circle me-2 text-card"></i>
                                        <strong>Observaciones:</strong>
                                        @if(trim($guard['info']) !== '')
                                            <textarea 
                                                name="taskInfo" 
                                                id="taskInfo" 
                                                class="form-control m-2" 
                                                rows="2" 
                                                readonly 
                                                style="resize: none; background-color: #f8f9fa; color: #495057;"
                                            >{{ $guard['info'] }}</textarea>
                                        @else
                                            <span class="text-muted">No hay observaciones para esta guardia.</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex justify-content-between pt-4 border-top">
            <a href="{{ route('teacher.home') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
@endsection