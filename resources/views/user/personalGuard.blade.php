@extends('layout')

@section('title', 'Profesorado | Mis Guardias de Hoy')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/guardsToday.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-4 fw-bold fs-2">Guardias Personales - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h1>

        @if(count($guards) === 0)
            <div class="alert alert-info text-center">
                No tienes guardias asignadas para hoy.
            </div>
        @else
            <div class="row g-4">
                @foreach($guards as $guard)
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-custom text-white">
                                <strong>Sesión | {{ $guard['hour'] ?? '—' }}</strong>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Profesor Ausente:</strong> {{ $guard['absent_teacher_name'] }}</p>
                                <p class="mb-1"><strong>Aula:</strong> {{ $guard['class_id'] ?? 'No asignada' }}</p>
                                <p class="mb-1"><strong>Observaciones:</strong> {{ $guard['text_guard'] ?? '—' }}</p>
                            </div>
                            <div class="card-footer text-muted text-end">
                                Última actualización: {{ \Carbon\Carbon::parse($guard['created_at'])->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex justify-content-between pt-3 border-top">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>
@endsection
