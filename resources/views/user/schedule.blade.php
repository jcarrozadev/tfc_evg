@extends('layout')

@section('title', 'Profesorado | Horario')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-4 fw-bold fs-2">Horario | {{ $user->name }} - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h1>

        @if(count($full) === 0)
            <div class="alert alert-info text-center">
                No tienes horario asignado.
            </div>
        @else
            @php
                $days = ['L' => 'Lunes', 'M' => 'Martes', 'X' => 'Miércoles', 'J' => 'Jueves', 'V' => 'Viernes'];

                $map = [];
                foreach ($full as $entry) {
                    $day = $entry['day'];
                    $session = $entry['session_id'];
                    $map[$day][$session] = $entry;
                }
            @endphp

            <table class="table schedule-table">
                <thead>
                    <tr>
                        <th>Sesión</th>
                        @foreach ($days as $key => $dayName)
                            <th>{{ $dayName }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sessions as $session)
                        @php
                            $start = \Illuminate\Support\Carbon::parse($session->hour_start)->format('H:i');
                            $end = \Illuminate\Support\Carbon::parse($session->hour_end)->format('H:i');
                            $time = "$start - $end";
                        @endphp
                        <tr>
                            <th>{{ $time }}</th>
                            @foreach ($days as $dayKey => $dayName)
                                @php
                                    $entry = $map[$dayKey][$session->id] ?? null;
                                @endphp
                                @if ($entry)
                                    <td class="{{ $entry['type'] === 'guard' ? 'guard-cell' : 'class-cell' }}">
                                        {{ $entry['label'] }}
                                    </td>
                                @else
                                    <td style="color:green;">LIBRE</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="alert alert-warning">
            <strong>Nota:</strong>
            <ul>
                <li>Por ahora sólo salen las guardias asignadas individual en el libro de guardias.</li>
                <li>En desarrollo para que salga el horario completo del profesorado.</li>
            </ul>
        </div>
        <div class="d-flex justify-content-between pt-3 border-top">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>
@endsection
