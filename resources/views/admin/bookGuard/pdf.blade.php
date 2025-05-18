@php
function toFullTime($time) {
    [$h, $m] = explode(':', $time);
    return sprintf('%02d:%02d:00', $h, $m);
}
@endphp

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 10px;
                color: #333;
                margin: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10px;
            }

            th, td {
                border: 1px solid #0B3B66;
                padding: 4px;
                text-align: center;
            }

            thead tr:first-child th {
                background-color: #0F4C81;
                color: white;
                font-weight: bold;
            }

            thead tr:nth-child(2) th {
                background-color: #2F75B5;
                color: white;
            }

            tbody tr:nth-child(even) {
                background-color: #f0f7ff;
            }

            tbody tr:nth-child(odd) {
                background-color: #ffffff;
            }

            tbody td:first-child {
                font-weight: bold;
                background-color: #d9e6f7;
                color: #0F4C81;
            }

            .logo {
                width: 100px;
                height: auto;
                margin: 0 auto;
                display: block;
            }

            h2 {
                text-align: center;
                color: #0F4C81;
                margin-bottom: 15px;
            }
        </style>
        <title>Libro de Guardias</title>
    </head>
    <body>
        <img src="{{ public_path('img/logo.png') }}" class="logo" style="width: 100px; height: auto; margin: 0 auto; display: block;">

        <h2>Libro de Guardias - {{ date('Y') }}</h2>

        <table>
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
                        <th>Profesores</th>
                        <th>Cursos</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($slots as $slot)
                    <tr>
                        <td>{{ $slot }}</td>
                        @foreach ($days as $day)
                            @php
                                $session = $sessions->first(fn($s) => $s->hour_start == toFullTime(explode('-', $slot)[0]) && $s->hour_end == toFullTime(explode('-', $slot)[1]));
                                $bookguard = $bookguards->first(fn($bg) => $bg->day == $day && $bg->session_id == optional($session)->id);
                                $users = $bookguard ? $bookguardUsers->where('bookguard_id', $bookguard->id)->values() : collect();
                            @endphp
                            <td>
                                @foreach ($users->take(2) as $u)
                                    {{ optional($teachers->firstWhere('id', $u->user_id))->name }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($users->take(2) as $u)
                                    @php
                                        $class = $classes->firstWhere('id', $u->class_id);
                                    @endphp
                                    {{ $class->num_class ?? '' }} {{ $class->course ?? '' }} {{ $class->code ?? '' }}<br>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>