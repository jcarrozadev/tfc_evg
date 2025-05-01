@extends('layout')

@php
    $days = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];
    $slots = [
        '8:15-9:10',
        '9:10-10:05',
        '10:05-11:00',
        '11:30-12:25',
        '12:25-13:20',
        '13:20-14:15',
    ];
@endphp

@section('title', 'Libro de Guardias')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bookGuard.css') }}">
@endpush

@section('content')
    @include('templates.navBar')
    <div class="container-custom shadow-sm bg-container-medium p-2 rounded">
        @include('components.titles.titleWeek', ['title' => 'Libro de Guardias'])

        <div class="container-fluid mt-5">
            <form method="POST" action="">
                @csrf
                <table class="table table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <th></th>
                            @foreach ($days as $day)
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
                            <tr @if ($slot === '11:00-11:30') style="background-color: #f3e600" @endif>
                                <td class="fw-bold">{{ $slot }}</td>
                                @foreach ($days as $day)
                                    <td>
                                        @for ($i = 0; $i < 2; $i++)
                                            <select name="guardias[{{ $day }}][{{ $slot }}][{{ $i }}][profesor]" class="form-select mb-1">
                                                <option disabled selected hidden ></option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                        @endfor
                                    </td>
                                    <td>
                                        @for ($i = 0; $i < 2; $i++)
                                            <select name="guardias[{{ $day }}][{{ $slot }}][{{ $i }}][clase]" class="form-select mb-1">
                                                <option disabled selected hidden ></option>
                                                @foreach ($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->num_class }} {{ $class->course }} {{ $class->code }}</option>
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
                    <input type="reset" class="btn btn-secondary" value="Resetear">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>                
            </form>
        </div>
    </div>
@endsection
