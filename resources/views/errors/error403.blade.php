@extends('layout')

@section('title', '403 - Acceso Denegado')

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
            <h1 class="display-3 text-danger fw-bold">403</h1>
            <h2>No tienes permisos para acceder aquí</h2>
            <p class="mb-4">Parece que no tienes autorización para ver esta página.</p>
            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Volver atrás</a>
            <a href="{{ $isAdmin ? route('teacher.index') : route('teacher.home') }}" class="btn btn-primary">
                Ir al inicio
            </a>
        </div>
    </body>
@endsection
