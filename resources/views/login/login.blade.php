@extends('layout')

@section('title', 'Login')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form>
                <h1>Crear Cuenta</h1>
                <div class="social-icons w-80">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i>Registrarse con Google</a>
                </div>
                <span>o tu correo para registrarte</span>
                <input type="text" placeholder="Name">
                <input type="email" placeholder="Email">
                <input type="password" placeholder="Password">
                <button>Registrarse</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form>
                <h1>Iniciar Sesión</h1>
                <div class="social-icons w-80">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i>Iniciar con Google</a>
                </div>
                <span>o correo y contraseña</span>
                <input type="email" placeholder="Email">
                <input type="password" placeholder="Password">
                <a href="#">¿Olvidaste la contraseña?</a>
                <button>Entrar</button>
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