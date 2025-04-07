<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GuardsEVG</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      margin: 0;
      background-color: #ffffff;
    }

    .header-evg {
      background-color: #c7c7c7;
      padding: 1rem 2rem;
    }

    .logo-evg {
      background-color: #003366;
      color: #ffffff;
      font-weight: bold;
      font-size: 1.8rem;
      padding: 0.5rem 1.5rem;
      border-radius: 12px;
      display: inline-block;
    }

    .navbar-evg {
      background-color: #003366;
    }

    .navbar-evg .nav-link {
      color: white;
      font-weight: 600;
      margin: 0 0.75rem;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .navbar-evg .nav-link:hover {
      background-color: #005599;
      transform: scale(1.05);
    }

    /* Evita que los enlaces sobresalgan o cambien de posición */
    .navbar-nav {
      gap: 0.25rem;
    }

    @media (max-width: 768px) {
      .logo-evg {
        font-size: 1.4rem;
        padding: 0.5rem 1rem;
      }
    }
  </style>
</head>
<body>

<header class="header-evg d-flex align-items-center">
    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="img-fluid me-3" style="max-width: 120px;">
</header>

  <nav class="navbar navbar-expand-lg navbar-evg">
    <div class="container-fluid">
      <div class="collapse navbar-collapse show">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 px-2">
          <li class="nav-item">
            <a class="nav-link" href="#">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Libro Guardias</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Guardias</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Profesores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Cursos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Clases</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  
</body>
</html>
