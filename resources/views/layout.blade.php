<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="Javier Arias Carroza | Zeus Martin Llera">
        <title>@yield('title')</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
        <link rel="stylesheet" href="{{ asset('css/common.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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