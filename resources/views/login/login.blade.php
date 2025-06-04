@extends('layout')

@section('title', 'Login')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script>
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 400); 
            });
        }, 5000);
    </script>
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="{{ route('custom.register') }}" autocomplete="off">
                @csrf
                <img src="{{ asset('img/logo.png') }}" alt="EVG Logo" class="mb-2 logo-sign-up" style="width: 200px;">
                <div class="social-icons w-80">
                    <a href="{{ route('google.login') }}" class="icon"><i class="fa-brands fa-google-plus-g"></i>Continuar con Google</a>
                </div>
                <span class="centered">o tu correo para registrarte</span>

                <input type="text" name="name" placeholder="Nombre completo" required>
                <input type="email" name="email" placeholder="Email" required autocomplete="off">
                <input type="password" name="password" placeholder="Contraseña (min 8 caracteres)" required autocomplete="new-password">
                <input type="password" name="password_confirmation" placeholder="Confirmar Contraseña" required autocomplete="new-password">
                <input type="text" name="phone" placeholder="Teléfono" required>
                <input type="text" name="dni" placeholder="DNI" required>

                <button type="submit" class="mb-3">Registrarse</button>
            </form>            
        </div>
        <div class="form-container sign-in active">
            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                <img src="{{ asset('img/logo.png') }}" alt="EVG Logo" class="mb-2" style="width: 200px;">
                <div class="social-icons w-80">
                    <a href="{{ route('google.login') }}" class="icon"><i class="fa-brands fa-google-plus-g"></i>Continuar con Google</a>
                </div>
                <span class="centered">o correo y contraseña</span>
                <input type="email" name="email" placeholder="Email" required autocomplete="off">
                <input type="password" name="password" placeholder="Contraseña" required autocomplete="new-password">
                <a href="{{ route('password.request') }}" class="forgotPassword">¿Olvidaste la contraseña?</a>
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
