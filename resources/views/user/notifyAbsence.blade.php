@extends('layout')

@section('title', 'Modificar Ausencia')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/consultAbsence.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker.min.css">
@endpush

@push('scripts')
    <script src="{{ asset('js/teacher/consultAbsence.js') }}"></script>
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
                <div class="mb-4">
                    <label for="datepicker" class="form-label fw-bold">Fecha</label>
                    <div class="input-group date" id="datepicker-container">
                        <input type="text" class="form-control py-2" id="datepicker" value="" required>
                        <span class="input-group-text bg-white">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hora inicio</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <select class="form-select py-2" required>
                                    <option value="" disabled selected hidden>Hora</option>
                                    @for($i = 8; $i <= 15; $i++)
                                        <option value="{{ $i }}">
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select py-2" required>
                                    <option value="" disabled selected hidden>Minutos</option>
                                    @for($i = 0; $i < 60; $i += 5)
                                        <option value="{{ $i }}">
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hora fin</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <select class="form-select py-2" required>
                                    <option value="" disabled selected hidden>Hora</option>
                                    @for($i = 8; $i <= 15; $i++)
                                        <option value="{{ $i }}">
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select py-2" required>
                                    <option value="" disabled selected hidden>Minutos</option>
                                    @for($i = 0; $i < 60; $i += 5)
                                        <option value="{{ $i }}">
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="tipo_ausencia" class="form-label fw-bold">Tipo de ausencia</label>
                    <select class="form-select py-2" id="tipo_ausencia" required>
                        <option value="" disabled selected hidden>Selecciona una opción</option>
                        <option value="Enfermedad">Enfermedad</option>
                        <option value="Consulta médica">Consulta médica</option>
                        <option value="Asuntos personales">Asuntos personales</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="justificante" class="form-label fw-bold">Justificante</label>
                    <div class="d-flex align-items-center">
                        <input type="file" class="form-control d-none" id="justificante">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('justificante').click()">
                            <i class="fas fa-upload me-1"></i> Subir justificante
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="form-label fw-bold">Descripción</label>
                    <textarea class="form-control" id="descripcion" rows="3" placeholder="Escribe una breve descripción de la ausencia"></textarea>
                </div>

                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i> Guardar cambios
                    </button>
                </div>
            </div>

            <div class="card-footer text-center text-muted py-3">
                <small>Sistema de Gestión de Ausencias - Escuela Virgen de Guadalupe © 2025 </small>
            </div>
        </div>
    </div>
@endsection
