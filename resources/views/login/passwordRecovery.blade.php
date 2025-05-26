@extends('layout')

@section('title', 'Restablecer contraseña')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/recoveryPassword.css') }}">
@endpush

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card neumorphic-card p-5 shadow-sm" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4 text-primary fw-bold">Recuperar contraseña</h3>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                <input type="email" name="email" class="form-control shadow-sm" id="email" placeholder="user@gmail.com" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Enviar enlace</button>
            </div>
        </form>
    </div>
</div>
@endsection
