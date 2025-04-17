@extends('layout')

@section('title', 'Gestor de Profesores')

@section('content')
    @include('templates.navBar')
    <div class="container-custom shadow-sm bg-container-medium p-2 rounded">
        @include('components.titles.title', ['title' => 'Profesores'])

        <div class="row mt-3 mb-3 ms-3 justify-content-end button-add">
            <div class="col-auto">
                @include('components.buttons.button', [
                    'text' => '<i class="fas fa-user-plus"></i>',
                    'route' => 'teacher.create',
                    'class' => 'btn btn-success button text-center',
                ])
            </div>
        </div>

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
                        'labelButton' => 'este profesor',
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/Datatables/datatable.js') }}"></script>
    <script src="{{ asset('js/sweetAlerts/swalDelete.js') }}"></script>
@endpush
