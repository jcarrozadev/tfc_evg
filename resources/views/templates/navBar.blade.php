<header class="header-evg d-flex align-items-center">
    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="img-fluid me-3" style="max-width: 120px;">
</header>

<nav class="navbar navbar-expand-lg navbar-evg mb-4">
    <div class="container-fluid">
        <div class="collapse navbar-collapse show">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 px-2">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.admin') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Libro Guardias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.guards') }}">Guardias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('teacher.index') }}">Profesores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('class.index') }}">Clases</a>
                </li>
            </ul>

            @if (Auth::user()->role_id === 1)
                <ul class="navbar-nav ms-auto px-2">
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" style="text-decoration: none;">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>
