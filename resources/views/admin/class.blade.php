@extends('layout')

@section('title', 'Gestor de Clases')

@section('content')
    @include('templates.navBar')
    <div class="container-custom shadow-sm bg-container-medium p-2 rounded">
        @include('components.titles.title', ['title' => 'Clases'])

        <div class="row mt-3 mb-3 ms-3 justify-content-end button-add">
            <div class="col-auto">
                <button class="btn btn-success button text-center" data-bs-toggle="modal" data-bs-target="#createClassModal">
                    <i class="fas fa-user-plus"></i>
                </button>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    @include('components.tables.table', [
                        'headers' => [
                            'ID' => 'id',
                            'Nº Clase' => 'num_class',
                            'Curso' => 'course',
                            'Letra' => 'code'
                        ],
                        'rows' => $classes,
                        'route' => 'class',
                        'actions' => ['edit', 'delete'],
                        'labelButton' => 'esta clase',
                        'editModal' => 'editClass',
                    ])
                </div>
            </div>
        </div>
    </div>

    @include('components.modals.classCreateModal')

@endsection

@push('scripts')
    <script src="{{ asset('js/Datatables/datatable.js') }}"></script>
    <script src="{{ asset('js/sweetAlerts/swalDelete.js') }}"></script>
    <script src="{{ asset('js/sweetAlerts/swalEdit.js') }}" ></script>
    <script src="{{ asset('js/modals/editClass.js') }}"></script>
    @include('components.sweetAlert.swal')
@endpush
