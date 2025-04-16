@props([
    'text' => null,
    'route' => '/',
    'class' => 'btn btn-sm',
])

@if ($route === 'logout')
    <form method="POST" action="{{ route($route) }}">
        @csrf
        <button type="submit" class="{{ $class }}">
            {!! $text !!}
        </button>
    </form>
@else
    <a href="{{ route($route) }}" class="{{ $class }}">
        {!! $text !!}
    </a>
@endif
