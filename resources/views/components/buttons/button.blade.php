@props([
    'text' => 'Botón',
    'route' => '/',
    'class' => 'btn btn-primary',
])

<a href=" {{ route($route) }} " class="{{ $class }}">
    {{ $text }}
</a>
