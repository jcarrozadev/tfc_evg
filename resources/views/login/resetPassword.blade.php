@extends('layout')

@section('title', 'Restablecer contraseña')

@section('content')
    <div class="form-container sign-in">
        <form method="POST" action="{{ route('password.update.fortify') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

            <h1>Restablecer contraseña</h1>

            <input type="password" name="password" placeholder="Nueva contraseña" required>
            <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>

            <button type="submit">Restablecer</button>
        </form>
    </div>
@endsection
