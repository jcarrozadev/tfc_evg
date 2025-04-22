@extends('layout')

@section('title', '404 - Página no encontrada')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
@endpush

@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
    $isAdmin = $user && $user->role && $user->role->name === 'Administrador';
@endphp

@section('content')
    <body class="error-page">
        <div class="container text-center py-5">
            <h1 class="display-3 text-warning fw-bold">404</h1>
            <h2>La página que buscas no existe</h2>
            <p class="mb-4">Es posible que la URL esté mal escrita o que la página haya sido movida.</p>
            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Volver atrás</a>
            <a href="{{ $isAdmin ? route('teacher.index') : route('teacher.home') }}" class="btn btn-primary">
                Ir al inicio
            </a>
        </div>
    </body>
@endsection
