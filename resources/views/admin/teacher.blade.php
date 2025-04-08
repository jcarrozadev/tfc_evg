@extends('layout')

@section('title', 'Panel Admin')

@section('content')
    @include('templates.navBar')
    <div class="container-custom shadow-sm bg-light">
        @include('components.titles.title', ['title' => 'Profesores'])
    </div>
@endsection