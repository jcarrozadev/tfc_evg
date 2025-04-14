<header class="header-evg d-flex align-items-center">
    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="img-fluid me-3" style="max-width: 120px;">
</header>

<nav class="navbar navbar-expand-lg navbar-evg mb-4">
    <div class="container-fluid">
        <div class="collapse navbar-collapse show">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 px-2">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Libro Guardias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Guardias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('teacher.index') }}">Profesores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('class.index') }}">Clases</a>
                </li>
            </ul>
        </div>
    </div>
</nav>  