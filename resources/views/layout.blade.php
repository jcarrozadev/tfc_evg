<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="author" content="Javier Arias Carroza | Zeus Martin Llera">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/common.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        @stack('styles')
    </head>
    <body>
        @include('header')

        @yield('content') 
        
        @include('footer')

        @include('projectCSS')
        @include('projectJS')

        @stack('scripts')
    </body>
</html>