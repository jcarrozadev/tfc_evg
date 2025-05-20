@extends('layout')

@section('title', 'Profesorado | Guardias del Día')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/guardsToday.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-5 fw-bold fs-2">
            <i class="fas fa-user-shield me-2"></i>
            Guardias del día - {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </h1>

        @if(! $guards || count($guards) === 0)
            <div class="bg-custom text-white text-center p-4 rounded mb-4">
                No hay guardias asignadas para hoy.
            </div>
        @else
            <div class="row g-4">
                @foreach($guards as $guard)
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-custom text-white d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>{{ $guard['hour'] ?? '—' }}</strong>
                                </span>

                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    {{ \Carbon\Carbon::parse($guard['created_at'])->format('H:i') }}
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $guard['absent_teacher_image'] ? asset('storage/' . $guard['absent_teacher_image']) : asset('img/default.png') }}"
                                         class="rounded-circle me-3 bg-light"
                                         style="width: 48px; height: 48px; object-fit: cover;"
                                         alt="Foto profesor ausente">
                                    <div>
                                        <p class="mb-0 fw-semibold">
                                            <i class="fas fa-user-times text-danger me-2"></i>Profesor Ausente:
                                        </p>
                                        <p class="mb-0">{{ $guard['absent_teacher_name'] }}</p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ ($guard['covering_teacher_image'] ?? null) ? asset('storage/' . $guard['covering_teacher_image']) : asset('img/default.png') }}"
                                         class="rounded-circle me-3 bg-light"
                                         style="width: 48px; height: 48px; object-fit: cover;"
                                         alt="Foto profesor sustituto">
                                    <div>
                                        <p class="mb-0 fw-semibold">
                                            <i class="fas fa-user-check text-success me-2"></i>Profesor Sustituto:
                                        </p>
                                        <p class="mb-0">{{ $guard['covering_teacher_name'] ?? 'Sin asignar' }}</p>
                                    </div>
                                </div>

                                <p class="mb-0">
                                    <i class="fas fa-door-closed me-2"></i>
                                    <strong>Aula:</strong> {{ $guard['class_id'] ?? 'No asignada' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex justify-content-between pt-4 border-top mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>
@endsection
