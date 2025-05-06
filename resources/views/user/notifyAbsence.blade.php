@extends('layout')

@section('title', 'Profesorado | Modificar Ausencia')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/absence.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css">
@endpush

@push('scripts')
    <script src="{{ asset('js/teacher/absence.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.es.min.js"></script>
@endpush

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
        <div class="card shadow-lg rounded-lg" style="width: 90%; max-width: 800px;">
            <div class="card-header bg-custom text-white py-3">
                <h2 class="h4 mb-0 text-center">NOTIFICAR AUSENCIA</h2>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('teacher.storeNotifyAbsence') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="datepicker" class="form-label fw-bold">Fecha</label>
                        <div class="input-group date" id="datepicker-container">
                            <input type="text" name="date" class="form-control py-2" id="datepicker" value="" autocomplete="off">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                        </div>
                    
                        <div class="mt-2 d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-today">Hoy</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-tomorrow">Mañana</button>
                        </div>                        
                    </div>                    

                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Sesión específica <span class="form-label fw-bold fst-italic">(No rellenar si falta completa)</span></label>
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <select name="session_id" class="form-select py-2" id="sessions">
                                        <option value="" disabled selected hidden>Selecciona una sesión</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->id }}">{{ $session->hour_start . " - " . $session->hour_end }}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="typeAbsence" class="form-label fw-bold">Tipo de ausencia</label>
                        <select name="typeAbsence" id="typeAbsence" class="form-select py-2" required>
                            <option value="" disabled selected hidden>Selecciona el motivo</option>
                            @foreach($reasons as $reason)
                                <option value="{{ $reason->id }}">{{ $reason->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="justify" class="form-label fw-bold">Justificante</label>
                        <div class="d-flex align-items-center">
                            <input type="file" name="justify" class="form-control d-none" id="justify">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('justify').click()">
                                <i class="fas fa-upload me-1"></i> Subir justificante
                            </button>
                            <span id="filename-label" class="ms-3 text-muted"></span>
                        </div>
                    </div>
                    

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Descripción</label>
                        <textarea name="description" class="form-control" id="description" rows="3" placeholder="Escribe una breve descripción de la ausencia"></textarea>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-paper-plane me-2"></i> Enviar
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-footer text-center text-muted py-3">
                <small>Sistema de Gestión de Ausencias - Escuela Virgen de Guadalupe © 2025</small>
            </div>
        </div>
    </div>
@endsection
