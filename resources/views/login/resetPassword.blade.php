@extends('layout')

@section('title', 'Restablecer contraseña')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/recoveryPassword.css') }}">
@endpush

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card neumorphic-card p-5 shadow-sm" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4 text-primary fw-bold">Restablecer contraseña</h3>

        <form method="POST" action="{{ route('password.update.fortify') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Nueva contraseña</label>
                <input type="password" name="password" id="password" class="form-control shadow-sm" placeholder="Nueva contraseña" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control shadow-sm" placeholder="Confirmar contraseña" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Restablecer</button>
            </div>
        </form>
    </div>
</div>
@endsection
