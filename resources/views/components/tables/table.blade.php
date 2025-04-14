@props([
    'headers' => [],
    'rows' => [],
    'route' => '',
    'actions' => [],
])

<table class="table table-striped" id="dataTable">
    <thead>
        <tr>
            @foreach ($headers as $label => $attr)
                <th>{{ $label }}</th>
            @endforeach

            @if ($actions)
                <th>Acciones</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
        <tr>
            @foreach ($headers as $label => $attr)
                <td>{{ $row->$attr ?? '-' }}</td>
            @endforeach

            @if ($actions)
                <td>
                    @if (in_array('edit', $actions))
                        <a href="{{ route($route . '.edit', $row->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    @endif

                    @if (in_array('delete', $actions))
                        <form action="{{ route($route . '.destroy', $row->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este registro?')">Eliminar</button>
                        </form>
                    @endif
                </td>
            @endif
        </tr>
@endforeach
    </tbody>
</table>
