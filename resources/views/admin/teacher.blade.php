@extends('layout')

@section('title', 'Panel Admin')

@section('content')
    @include('templates.navBar')
    <div class="container-custom shadow-sm bg-light">
        @include('components.titles.title', ['title' => 'Profesores'])

        <div class="container">
            <div class="row">
                <div class="col-12">
                    @include('components.tables.table', [
                        'headers' => [
                            'Nombre' => 'name',
                            'Correo' => 'email',
                            'Teléfono' => 'phone',
                            'Disponible' => 'available',
                        ],
                        'rows' => $teachers,
                        'route' => 'teacher',
                        'actions' => ['edit', 'delete'],
                    ])
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    @include('components.buttons.button', [
                        'text' => 'Agregar Profesor',
                        'route' => 'teacher.create',
                        'class' => 'btn btn-success',
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/Datatables/datatable.js') }}"></script>
@endpush
