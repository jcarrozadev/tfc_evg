@extends('layout')

@section('title', 'Profesorado | Horario')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
@endpush

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-4 fw-bold fs-2 pt-5 text-white">Horario de {{ $user->name }} - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h1>

        @if(count($bookguard) === 0)
            <div class="alert alert-info text-center">
                No tienes horario asignado.
            </div>
        @else
            @php

                $days = ['L' => 'Lunes', 'M' => 'Martes', 'X' => 'Miércoles', 'J' => 'Jueves', 'V' => 'Viernes'];

                $guardMap = [];
                foreach ($bookguard as $entry) {
                    $day = $entry['day'];
                    $start = \Illuminate\Support\Carbon::parse($entry['hour_start'])->format('H:i');
                    $guardMap[$day][$start] = true;
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
                            $time = $start . ' - ' . $end;
                        @endphp
                        <tr>
                            <th>{{ $time }}</th>
                            @foreach ($days as $dayKey => $dayName)
                                @if (!empty($guardMap[$dayKey][$start]))
                                    <td class="guard-cell">GUARDIA</td>
                                @else
                                    <td></td>
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
