<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <!-- 'request()->is() comprueba si estamos parados en la URL indicada. Si se cumple la condición, se realiza lo que está a la derecha de '?'. En cambio, si no se cumple, se hace lo que está a la derecha de ':' -->
            <li class="nav-item">
                <a class="nav-link text-body rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}" href="#">
                    <i class="bi bi-file-earmark-text"></i>
                    Todos los convenios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-body rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}" href="#">
                    <i class="bi bi-clock"></i>
                    Solicitudes pendientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-body rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}" href="#">
                    <i class="bi bi-person"></i>
                    Usuarios de secretaría
                </a>
            </li>

            <!-- Nuevo elemento principal llamado Gestión  Académica -->
            <li class="nav-item rounded-pill" id="academicoMenu">
                <a class="nav-link text-body rounded-pill" href="#" id="academicoToggle"  aria-expanded="false" aria-controls="academicoSubmenu">
                    <i class="bi bi-book"></i>
                    Gestión Académica
                </a>
                <!-- Submenú para Académico que solo se muestra al hacer clic -->
                <ul id="academicoSubmenu" class="collapse nav flex-column ms-3 {{request()->is('teachers*') || request()->is('careers*') || request()->is('departments*') ? 'show' : ''}}">
                    <li class="nav-item">
                        <a class="nav-link text-body rounded-pill {{request()->is('teachers*') ? 'active-nav-link' : ''}} " href="{{route('teachers.index')}}">
                            <i class="bi bi-people"></i>
                            Docentes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-body rounded-pill {{request()->is('careers*') ? 'active-nav-link' : ''}}" href="{{route('careers.index')}}">
                            <i class="bi bi-journal"></i>
                            Carreras
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link text-body rounded-pill {{request()->is('departments*') ? 'active-nav-link' : ''}}" href="{{route('departments.index')}}">
                            <i class="bi bi-journal"></i>
                            Departamentos
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item ">
                <a class="nav-link text-body rounded-pill {{request()->is('companies*') ? 'active-nav-link' : ''}}" href="{{route('companies.index')}}">
                    <i class="bi bi-building"></i>
                    Empresas
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link text-body rounded-pill {{request()->is('COMPLETAR*') ? 'active-nav-link' : ''}}" href="#">
                    <i class="bi bi-mortarboard"></i>
                    Alumnos
                </a>
            </li>
        </ul>
    </div>
</nav>
