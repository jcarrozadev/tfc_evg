@extends('layout')

@section('title', 'Restablecer contraseña')

@section('content')
    <div class="form-container sign-in">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <h1>Recuperar contraseña</h1>
            <span>Introduce tu correo electrónico</span>

            @if (session('status'))
                <div class="alert alert-success mt-2">
                    {{ session('status') }}
                </div>
            @endif

            <input type="email" name="email" placeholder="Correo electrónico" required>
            <button type="submit">Enviar enlace</button>
        </form>
    </div>
@endsection
