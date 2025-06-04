@extends('layout')

@section('title', 'Profesorado | Consulta Ausencias')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/absence.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-5 fw-bold fs-2">
            <i class="fa-solid fa-calendar-xmark me-2"></i>
            Ausencias generadas | {{ $user->name }} - {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </h1>

        <div id="absence-list">
            @forelse ($absences as $absence)
                <div class="absence-card mb-4 shadow-sm rounded-4 position-relative" data-id="{{ $absence->id }}">
                    <div class="absence-card__header text-white d-flex align-items-center gap-2 px-4 py-2 rounded-top-4">
                        <i class="fa-solid fa-calendar-xmark fa-lg"></i>
                        <span class="fw-semibold">Tu Ausencia · Ref. {{ $absence->id }}</span>

                        <span class="badge bg-light text-dark ms-auto">
                            {{ \Carbon\Carbon::parse($absence->created_at)->format('d M Y') }}
                        </span>
                    </div>
                    <div class="absence-card__body p-4">
                        <div class="row gy-3">
                            <div class="col-md-6 d-flex flex-column gap-3">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        <i class="fa-regular fa-clock me-1"></i>Sesión
                                    </h6>
                                    <p class="mb-0 text-muted">
                                        @if($absence->hour_start && $absence->hour_end)
                                            {{ $absence->hour_start }} - {{ $absence->hour_end }}
                                        @else
                                            Todo el día
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <h6 class="fw-bold mb-1">
                                        <i class="fa-solid fa-circle-info me-1"></i>Razón
                                    </h6>
                                    @if ($absence->reason_description)
                                        <p class="mb-0 text-muted">{{ $absence->reason_description }}</p>
                                    @else
                                        <p class="mb-0 text-muted">No se ha proporcionado una descripción.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 d-flex flex-column gap-3">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        <i class="fa-solid fa-tag me-1"></i>Tipo de ausencia
                                    </h6>
                                    <p class="mb-0">
                                        <span class="text-muted">{{ $absence->reason_name }}</span>

                                        @if($absence->justify)
                                            <a  href="{{ Storage::url($absence->justify) }}"
                                                class="badge bg-success ms-2 text-decoration-none"
                                                target="_blank">
                                                <i class="fa-solid fa-paperclip me-1"></i>Justificante
                                            </a>
                                        @else
                                            <span class="badge bg-secondary ms-2">
                                                <i class="fa-regular fa-circle-xmark me-1"></i>Sin justificante
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <h6 class="fw-bold mb-1">
                                        <i class="fa-solid fa-shield-check me-1"></i>Estado de la guardia
                                    </h6>
                                    <p class="mb-0">
                                        @if($absence->guards_count > 0)
                                            <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Cubierta</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i>Sin cubrir</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div>
                            <h6 class="fw-bold mb-1 d-flex align-items-center">
                                <i class="fa-solid fa-user-tie me-1"></i>
                                Info - Profesor sustituto
                                <span class="ms-1 small text-card-absence">(clic para editar)</span>
                            </h6>
                            <p class="form-control-plaintext absence-info description-field text-muted"
                               data-update-url="{{ route('teacher.absences.updateInfo', $absence->id) }}">
                                {{ $absence->info }}
                            </p>
                            <div class="substitute-file-link mt-2">
                                @foreach($absence->files as $file)
                                    <div class="d-flex align-items-center justify-content-between mb-1" data-file-id="{{ $file->id }}">
                                        <span>
                                            <i class="fa-solid fa-paperclip me-1"></i>{{ $file->original_name }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    No tienes ausencias registradas para hoy.
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-between pt-3 border-top">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/teacher/consultAbsence.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/modular/sortable.core.esm.min.js" type="module"></script>
@endpush