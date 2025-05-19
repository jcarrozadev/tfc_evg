@extends('layout')

@php
    $daysTitle = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];
    $days = ['L', 'M', 'X', 'J', 'V'];
    $slots = [
        '8:15-9:10',
        '9:10-10:05',
        '10:05-11:00',
        '11:30-12:25',
        '12:25-13:20',
        '13:20-14:15',
    ];
@endphp

@section('title', 'Administración | Libro de Guardias')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bookGuard.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush

@section('content')
    @include('templates.navBar')
    <div class="container-custom shadow-sm bg-container-medium p-2 rounded">
        @include('components.titles.titleWeek', ['title' => 'Libro Guardias'])

        <div class="d-flex justify-content-end">
            <a href="{{ route('bookGuard.downloadPdf') }}" class="btn btn-danger button text-center align-middle d-flex align-items-center justify-content-center" style="height: 30px;">
                <i class="bi bi-file-earmark-pdf-fill fs-6"></i>
            </a>
        </div>

        <div class="container-fluid mt-3">
            @php
                function toFullTime($time) {
                    [$h, $m] = explode(':', $time);
                    return sprintf('%02d:%02d:00', $h, $m);
                }

                function findSessionId($sessions, $slot) {
                    [$start, $end] = explode('-', $slot);
                    $start = toFullTime($start);
                    $end = toFullTime($end);

                    return optional($sessions->first(fn($session) => $session->hour_start === $start && $session->hour_end === $end))->id;
                }

                function findBookguardUsersByPosition($bookguardUsers, $bookguardId) {
                    return collect($bookguardUsers)
                        ->where('bookguard_id', $bookguardId)
                        ->values();
                }
            @endphp
            <form method="POST" action="{{ route('bookGuard.store') }}" id="form-guardias">
                @csrf
                <table class="table table-bordered text-center align-middle w-100">
                    <thead>
                        <tr>
                            <th></th>
                            @foreach ($daysTitle as $day)
                                <th colspan="2">{{ $day }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th></th>
                            @foreach ($days as $day)
                                <th>PROFESORES</th>
                                <th>CURSOS</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slots as $index => $slot)
                            <tr>
                                <td class="fw-bold slot-session">{{ $slot }}</td>
                                @foreach ($days as $dIndex => $day)
                                    @php
                                        $dayLetter = $day;
                                        $sessionId = findSessionId($sessions, $slot);
                                        $bookguard = $bookguards->firstWhere(fn($bg) => $bg->day === $dayLetter && $bg->session_id === $sessionId);
                                        $users = $bookguard ? findBookguardUsersByPosition($bookguardUsers, $bookguard->id) : collect();
                                    @endphp

                                    {{-- TEACHERS --}}
                                    <td>
                                        @for ($i = 0; $i < 2; $i++)
                                            @php $userId = $users[$i]['user_id'] ?? null; @endphp
                                            <select name="guards[{{ $dayLetter }}][{{ $slot }}][{{ $i }}][user_id]" class="form-select mb-1 profesor-select">
                                                <option value="-" disabled {{ is_null($userId) ? 'selected' : '' }}>--</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" {{ $teacher->id == $userId ? 'selected' : '' }}>
                                                        {{ $teacher->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endfor
                                    </td>

                                    {{-- CLASSES --}}
                                    <td>
                                        @for ($i = 0; $i < 2; $i++)
                                            @php $classId = $users[$i]['class_id'] ?? null; @endphp
                                            <select name="guards[{{ $dayLetter }}][{{ $slot }}][{{ $i }}][class_id]" class="form-select mb-1">
                                                <option value="-" disabled {{ is_null($classId) ? 'selected' : '' }}>--</option>
                                                @foreach ($classes as $class)
                                                    <option value="{{ $class->id }}" {{ $class->id == $classId ? 'selected' : '' }}>
                                                        {{ $class->num_class }} {{ $class->course }} {{ $class->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endfor
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <input type="reset" id="reset" class="btn btn-secondary" value="Restablecer">
                    <button id="btn-confirmar" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @include('sweetAlerts.swal1')
    <script>
        const routeReset = "{{ route('bookGuard.reset.complete') }}";
        const routeResetClases = "{{ route('bookGuard.reset.classes') }}";
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('js/bookGuard.js') }}"></script>
@endpush