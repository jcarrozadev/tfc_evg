@extends('layout')

@section('title', 'Administración | Gestor de Guardias')

@section('content')
    @include('templates.navBar')

    <div class="container-custom shadow-sm bg-container-medium p-2 rounded">
        @include('components.titles.title', ['title' => 'Guardias'])    
        <div class="d-flex justify-content-center align-items-center" style="min-height: 50vh">
            <h1 class="fs-4 text-muted">{{ $message }}</h1>
        </div>
    </div>
@endsection
