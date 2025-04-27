@extends('layout')

@section('title', 'Gestor de Guardias')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/guards.css') }}">
@endpush

@section('content')

@include('templates.navBar')

<div class="container-custom shadow-sm bg-container-medium p-2 rounded">
    @include('components.titles.title', ['title' => 'Gestor de Guardias'])

    <div class="row g-5 p-4">
        <div class="col-md-8 d-flex flex-column gap-4">
            @foreach ($absences as $absence)
            <div class="card shadow-sm p-3 rounded bg-white">
                <div class="row align-items-center text-center">
                    
                    <div class="col-3">
                        <div class="fw-bold text-primary">{{ $absence->hour_start . " - " . $absence->hour_end}}</div>
                    </div>
                    
                    <div class="col-5">
                        <div class="fw-semibold">{{ $absence->user_name }}</div>
                        <div class="small text-muted">{{ $absence->reason_name }}</div>
                    </div>
                    
                    <div class="col-4">
                        <div class="dropzone border rounded p-2 bg-light" style="min-height: 50px;">
                            <span class="text-muted">Arrastra profesor</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="col-md-4 d-flex flex-column gap-3">
            @foreach ($teachers as $teacher)
                <div class="draggable card p-2 bg-custom text-white shadow-sm rounded d-flex align-items-center gap-2" draggable="true">
                    <div class="bg-light rounded-circle" style="width: 32px; height: 32px;"></div>
                    <span class="fw-semibold">{{ $teacher->name }}</span>
                </div>
            @endforeach
 
        </div>

    </div>
</div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="{{ asset('js/guards.js') }}"></script>
@endpush