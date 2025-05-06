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
            <div class="col-md-8 d-flex flex-column gap-4">
                @foreach ($absences as $absence)
                    @php
                        $firstSession = collect($absence->sessions)->pluck('id')->sort()->first();
                        $color = $firstSession ? ($sessionColors[$firstSession] ?? '#ccc') : '#ccc';
                    @endphp

                    <div class="card shadow-sm p-3 rounded bg-white"
                        style="border-left: 5px solid {{ $color }};">
                        <div class="fw-semibold mb-2">{{ $absence->user_name }}</div>
                        <div class="small text-muted mb-2">{{ $absence->reason_name }}</div>
                        <div class="small text-muted mb-4">
                            Ausencia generada {{ $absence->created_at->format('d/m/Y H:i') }}
                        </div>

                        @foreach ($absence->sessions as $session)
                            <div class="row align-items-center text-center mb-4">
                                <div class="col-4">
                                    <div class="fw-bold text-primary">
                                        {{ $session['hour_start'] . " - " . $session['hour_end'] }}
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="dropzone border rounded p-2 bg-light"
                                        style="min-height: 50px;"
                                        data-absence-id="{{ $absence->id }}"
                                        data-session-id="{{ $session['id'] }}">
                                        <span class="text-muted">Arrastra profesor</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

            </div>

            <div class="col-md-4 d-flex flex-column gap-3">
                @foreach ($teachers as $teacher)
                    @php
                        $todayLetter = $todayLetter ?? 'L';
                        $teacherSessions = array_map(function ($id) use ($todayLetter) {
                            return ['day' => $todayLetter, 'session_id' => $id];
                        }, $teacher->session_ids ?? []);
                    
                        $sortedSessionIds = collect($teacher->session_ids ?? [])->sort()->values();
                        $mainSessionId = $sortedSessionIds->first();
                        $color = $mainSessionId ? ($sessionColors[$mainSessionId] ?? '#ccc') : '#ccc';
                    @endphp
                    
                    <div class="draggable card p-2 bg-custom text-white shadow-sm rounded d-flex align-items-center gap-2"
                        draggable="true"
                        data-teacher-id="{{ $teacher->id }}"
                        data-sessions='@json($teacherSessions)'
                        style="border-left: 5px solid {{ $color }};">
                        <div class="bg-light rounded-circle" style="width: 32px; height: 32px;"></div>
                        <span class="fw-semibold">{{ $teacher->name }}</span>
                    </div>
                @endforeach
            </div>

            <button id="saveAssignmentsBtn" class="btn btn-primary mt-3">Guardar cambios</button>

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