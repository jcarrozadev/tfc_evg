@extends('layout')

@section('title', 'Profesorado | Consulta Ausencias')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/absence.css') }}">
@endpush

@section('content')
    <div class="container py-4">
        <h1 class="text-center mb-4 fw-bold fs-2">Ausencias generadas | {{ $user->name }} - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h1>

        @forelse ($absences as $absence)
            <div class="card shadow-sm rounded-lg mb-4">
                <div class="card-header bg-custom text-white">
                    <strong>Ausencia ID: {{ $absence->id }}</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fecha</label>
                        <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($absence->created_at)->format('d/m/Y') }}</p>
                    </div>

                    @if (!is_null($absence->hour_start) && !is_null($absence->hour_end))
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sesión</label>
                            <p class="form-control-plaintext">{{ $absence->hour_start . ' - ' . $absence->hour_end}}</p>
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sesión</label>
                            <p class="form-control-plaintext">Todo el día</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de ausencia</label>
                        <p class="form-control-plaintext">{{ $absence->reason_name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sesiones afectadas</label>
                        <p class="form-control-plaintext">
                            @if (!empty($absence->session_ids))
                                {{ implode(', ', $absence->session_ids) }}
                            @else
                                Ninguna sesión asociada
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">
                No tienes ausencias registradas para hoy.
            </div>
        @endforelse

        <div class="d-flex justify-content-between pt-3 border-top">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>
@endsection
