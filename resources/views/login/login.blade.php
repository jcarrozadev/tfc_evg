@extends('layout')

@section('title', 'Login')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        {{ $error }}
        @endforeach
    </div>
    @endif
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1>Crear Cuenta</h1>
                <div class="social-icons w-80">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i>Registrarse con Google</a>
                </div>
                <span>o tu correo para registrarte</span>
            
                <input type="text" name="name" placeholder="Nombre completo" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Contraseña (min 8 caracteres)" required>
                <input type="password" name="password_confirmation" placeholder="Confirmar Contraseña" required>
                <input type="text" name="phone" placeholder="Teléfono" required>
                <input type="text" name="dni" placeholder="DNI" required>
            
                <button type="submit">Registrarse</button>
            </form>            
        </div>
        <div class="form-container sign-in">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1>Iniciar Sesión</h1>
                <div class="social-icons w-80">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i>Iniciar con Google</a>
                </div>
                <span>o correo y contraseña</span>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <a href="{{ route('password.request') }}">¿Olvidaste la contraseña?</a>
                <button type="submit">Entrar</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>¡Bienvenido de nuevo!</h1>
                    <p>Ingresa tus datos personales para usar todas las funciones de profesor</p>
                    <button class="hidden" id="login">Iniciar Sesión</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>¡Hola, Profesor!</h1>
                    <p>Regístrate con tus datos personales para usar todas las funciones de profesor</p>
                    <button class="hidden" id="register">Regístrate</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endpush