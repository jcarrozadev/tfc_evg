<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="author" content="Javier Arias Carroza | Zeus Martin Llera">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @stack('styles')
    </head>
    <body>

        @include('header')

        @yield('content') 
        
        @include('footer')

        @stack('scripts')
    </body>
</html>