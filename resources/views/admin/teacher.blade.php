@extends('layout')

@section('title', 'Administración | Gestor de Profesores')

@section('content')
    @include('templates.navBar')
    <div class="container-custom shadow-sm bg-container-medium p-2 rounded">
        @include('components.titles.title', ['title' => 'Profesores'])

        <div class="row mt-3 mb-3 ms-3 d-flex justify-content-end">
            <div class="col-auto">
                <button class="btn btn-success button text-center align-middle d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#createTeacherModal">
                    <i class="fas fa-plus fs-6"></i>
                </button>
            </div>
        </div>

        @include('components.tables.table', [
            'headers' => [
                'Nombre' => 'name',
                'Correo' => 'email',
                'Teléfono' => 'phone',
                'DNI' => 'dni',
                'Disponible' => 'available',
            ],
            'rows' => $teachers,
            'route' => 'teacher',
            'actions' => ['edit', 'delete'],
            'labelButton' => 'este profesor',
            'editModal' => 'editTeacher',
        ])

    </div>

    @include('components.modals.teacherCreateModal')

@endsection

@push('scripts')
    <script src="{{ asset('js/Datatables/datatable.js') }}"></script>
    <script src="{{ asset('js/sweetAlerts/swalCreate.js') }}"></script>
    <script src="{{ asset('js/sweetAlerts/swalDelete.js') }}"></script>
    <script src="{{ asset('js/sweetAlerts/swalEdit.js') }}" ></script>
    <script src="{{ asset('js/modals/addTeacher.js') }}"></script>
    <script src="{{ asset('js/modals/editTeacher.js') }}"></script>
    @include('components.sweetAlert.swal')
@endpush
