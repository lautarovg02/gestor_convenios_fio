<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <!-- 'request()->is() comprueba si estamos parados en la URL indicada. Si se cumple la condición, se realiza lo que está a la derecha de '?'. En cambio, si no se cumple, se hace lo que está a la derecha de ':' -->
            <li class="nav-item rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}">
                <a class="nav-link text-body" href="#">
                    <i class="bi bi-file-earmark-text"></i>
                    Todos los convenios
                </a>
            </li>
            <li class="nav-item rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}">
                <a class="nav-link text-body" href="#">
                    <i class="bi bi-clock"></i>
                    Solicitudes pendientes
                </a>
            </li>
            <li class="nav-item rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}">
                <a class="nav-link text-body" href="#">
                    <i class="bi bi-person"></i>
                    Usuarios de secretaría
                </a>
            </li>
            <li class="nav-item rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}">
                <a class="nav-link text-body" href="#">
                    <i class="bi bi-people"></i>
                    Docentes
                </a>
            </li>
            <li class="nav-item rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}">
                <a class="nav-link text-body" href="#">
                    <i class="bi bi-journal"></i>
                    Carreras
                </a>
            </li>
            <li class="nav-item rounded-pill {{request()->is('companies*') ? 'active-nav-link' : ''}}">
                <a class="nav-link text-body" href="{{route('companies.index')}}">
                    <i class="bi bi-building"></i>
                    Empresas
                </a>
            </li>
            <li class="nav-item rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}">
                <a class="nav-link text-body" href="#">
                    <i class="bi bi-mortarboard"></i>
                    Alumnos
                </a>
            </li>
        </ul>
    </div>
</nav>
