@extends('layout')

@section('title', 'Gestor de Clases')

@section('content')
    @include('templates.navBar')
    <div class="container-custom shadow-sm bg-container-medium p-2 rounded">
        @include('components.titles.title', ['title' => 'Clases'])

        <div class="row mb-3 me-4 justify-content-end button-add">
            <button class="btn btn-success button text-center" data-bs-toggle="modal" data-bs-target="#createClassModal">
                <i class="fas fa-user-plus"></i>
            </button>
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
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/Datatables/datatable.js') }}"></script>
    @include('components.sweetAlert.swal')
@endpush
