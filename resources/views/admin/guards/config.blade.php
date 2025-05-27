@extends('layout')

@section('title', 'Administración | Gestor de Guardias')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/guards.css') }}">
@endpush

@section('content')

    @include('templates.navBar')

    <div class="container-custom shadow-sm bg-container-medium p-2 rounded">
        @include('components.titles.title', ['title' => 'Guardias'])

        <div class="row g-5 p-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <strong>Instrucciones:</strong>
                    <ul>
                        <li>Arrastra un profesor a la sesión correspondiente.</li>
                        <li>Los colores están en conjunto con la sesión que se puede cubrir.</li>
                        <li>Haz clic en "Guardar cambios" para aplicar los cambios.</li>
                    </ul>
                </div>
                <div class="alert alert-warning">
                    <strong>Nota:</strong>
                    <ul>
                        <li>Si un profesor está repetido dos veces con distintos colores, puede cubrir dos sesiones el mismo día.</li>
                        <li>Si un profesor que está en el libro de guardias para este día, está ausente, no aparece.</li>
                        <li>Cuando estes seguro de tener bien asignadas las guardias puedes enviar los correos.</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8 d-flex flex-column gap-4">
                @foreach ($absences as $absence)
                    @php
                        $isFullDay = is_null($absence->hour_start) && is_null($absence->hour_end);
                        $color = $isFullDay ? '#ccc' : ($sessionColors[collect($absence->sessions)->pluck('id')->sort()->first()] ?? '#ccc');
                    @endphp
                    
                    <div class="card shadow-sm p-3 rounded bg-white position-relative"
                        style="border-left: 8px solid {{ $color }};">

                        @if ($absence->class_id === null)
                            <span class="badge bg-danger text-white position-absolute top-0 end-0 m-2">
                                No rellenable
                            </span>
                        @endif

                        <div class="fw-semibold mb-2">{{ $absence->user_name }}</div>
                        <div class="small text-muted mb-2">{{ $absence->reason_name }}</div>
                        <div class="small text-muted mb-4">
                            Ausencia generada {{ $absence->created_at->format('d/m/Y H:i') }}
                        </div>

                        @foreach ($absence->sessions as $session)
                            @php
                                $sessionColor = $sessionColors[$session['id']] ?? '#ccc';
                                $assigned = $assignedGuards
                                    ->first(fn($g) => $g->absence_id == $absence->id && $g->hour == $session['hour_start']);
                                $assignedTeacher = $assigned ? $teachers->firstWhere('id', $assigned->user_sender_id) : null;
                            @endphp
                            <div class="row align-items-center text-center mb-4"
                                @if ($isFullDay)
                                    style="border-left: 6px solid {{ $sessionColor }}; padding-left: 8px; border-radius: 4px;"
                                @endif
                            >  
                                <div class="col-4">
                                    <div class="fw-bold text-primary">
                                        {{ 
                                            \Carbon\Carbon::parse($session['hour_start'])->format('H:i') 
                                            . " - " . 
                                            \Carbon\Carbon::parse($session['hour_end'])->format('H:i') 
                                            . " | " . ($absence->class_id !== null ? "{$absence->class_number}{$absence->class_course} {$absence->class_code}" : 'LIBRE') 
                                        }}
                                    </div>
                                </div>
                                <div class="col-8">
                                    @if ($absence->class_id !== null)
                                        <div class="dropzone border rounded p-2 bg-light"
                                            style="min-height: 50px;"
                                            data-absence-id="{{ $absence->id }}"
                                            data-session-id="{{ $session['id'] }}">

                                            @if ($assignedTeacher)
                                                <div class="draggable card p-2 bg-custom text-white shadow-sm rounded d-flex align-items-center gap-2"
                                                    draggable="true"
                                                    data-teacher-id="{{ $assignedTeacher->id }}"
                                                    style="border-left: 8px solid {{ $sessionColor }};">
                                                    <img src="{{ $assignedTeacher->image_profile ? asset('storage/' . $assignedTeacher->image_profile) : asset('img/default.png') }}"
                                                        class="rounded-circle bg-light"
                                                        style="width: 32px; height: 32px; object-fit: cover;">
                                                    <span class="fw-semibold">{{ $assignedTeacher->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Arrastra profesor</span>
                                            @endif

                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">No requiere sustitución</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

            </div>

            <div class="col-md-4 d-flex flex-column gap-3">
                @foreach ($teachers as $teacher)
                    @php
                        $color = $sessionColors[$teacher->session_id] ?? '#ccc';
                        $sessionData = [['day' => $todayLetter, 'session_id' => $teacher->session_id]];
                    @endphp
            
                    <div class="draggable card p-2 bg-custom text-white shadow-sm rounded d-flex align-items-center gap-2"
                        draggable="true"
                        data-teacher-id="{{ $teacher->id }}"
                        data-sessions='@json($sessionData ?? [])'
                        style="border-left: 8px solid {{ $color }};">
                        
                        <img src="{{ $teacher->image_profile ? asset('storage/' . $teacher->image_profile) : asset('img/default.png') }}"
                            class="rounded-circle bg-light"
                            style="width: 32px; height: 32px; object-fit: cover;">
                            
                        <span class="fw-semibold">{{ $teacher->name }}</span>
                    </div>

                @endforeach
            </div>
            <div class="col-12">
                <button id="saveAssignmentsBtn" class="btn btn-primary mt-3 w-100">
                    <i class="fa fa-save me-2"></i> Guardar cambios
                </button>
                <button id="sendEmailsBtn" class="btn btn-success mt-3 w-100">
                    <i class="fa fa-envelope me-2"></i> Enviar correos
                </button>
                <button id="sendWhatsappsBtn" class="btn btn-success mt-3 w-100">
                    <i class="fab fa-whatsapp me-2"></i> Enviar Whatsapps
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const sessionColors = @json($sessionColors);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="{{ asset('js/guards.js') }}"></script>
@endpush