@props([
    'headers' => [],
    'rows' => [],
    'route' => '',
    'actions' => [],
    'labelButton' => 'este registro',
    'editModal' => '',
])

<table class="table table-striped datatable rounded" id="dataTable">
    <thead>
        <tr>
            @foreach ($headers as $label => $attr)
                <th>{{ $label }}</th>
            @endforeach

            @if ($actions)
                <th>Acciones</th>
            @endif
        </tr>
        <tr>
            @foreach ($headers as $label => $attr)
                <th>
                    <input 
                        type="text" 
                        class="form-control column-filter text-center" 
                        placeholder="Buscar..."
                        data-column="{{ $loop->index }}"
                    >
                </th>
            @endforeach

            @if ($actions)
                <th></th>
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
                    @if (in_array('edit', $actions) && $editModal)
                        <button 
                            class="btn btn-sm button-edit button" style="height: 30px"
                            data-bs-toggle="modal"
                            data-bs-target="#{{ $editModal }}Modal"
                            onclick="fillEditModal({{ $row }})"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                    @elseif (in_array('edit', $actions))
                        <a href="{{ route($route . '.edit', $row->id) }}" class="btn btn-sm button-edit button">
                            <i class="fas fa-edit"></i>
                        </a>
                    @endif

                    @if (in_array('delete', $actions))
                    <button 
                        class="btn btn-sm btn-danger button" style="height: 30px"
                        onclick="deleteItem('{{ route($route . '.destroy', $row->id) }}', '{{ $labelButton }}')">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

@if ($editModal)
    @include('components.modals.' . $editModal)
@endif
