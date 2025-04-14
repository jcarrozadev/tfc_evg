@props([
    'text' => null,
    'route' => '/',
    'class' => 'btn btn-sm',
])

<a href="{{ route($route) }}" class="btn btn-sm {{ $class }}">
    {!! $text !!}
</a>
