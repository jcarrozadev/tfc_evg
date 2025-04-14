@extends('layout')

@section('title', 'Clases')

@section('content')
    @include('templates.navBar')
    <div class="container-custom shadow-sm bg-container-medium p-2 rounded">
        @include('components.titles.title', ['title' => 'Clases'])

        <div class="row mt-3 mb-3 ms-3 justify-content-end button-add">
            <div class="col-auto">
                @include('components.buttons.button', [
                    'text' => '<i class="fas fa-user-plus"></i>',
                    'route' => 'class.create',
                    'class' => 'btn btn-success button text-center',
                ])        
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
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/Datatables/datatable.js') }}"></script>
@endpush
