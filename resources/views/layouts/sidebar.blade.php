<nav class="col-md-2 d-none d-md-block sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#convenioSubmenu" role="button" aria-expanded="{{ request()->is('agreements*') ? 'true' : 'false' }}" aria-controls="convenioSubmenu">
                    <i class="bi bi-file-earmark-text"></i>
                    Convenios
                </a>
                <ul id="convenioSubmenu" class="collapse nav flex-column ms-3 {{ request()->is('agreements*') ? 'show' : '' }}">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('agreements') ? 'active-nav-link' : '' }}" href="{{ route('agreements.index') }}">
                            <i class="bi bi-list-ul"></i>
                            Todos los convenios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="#"
                            data-bs-toggle="modal"
                            data-bs-target="#crearConvenioModal"
                            style="background-color: #b2f2bb; font-weight: 600; border: 2px solid #212529; border-radius: 6px;">
                            <i class="bi bi-plus-circle"></i>
                            Crear nuevo convenio
                        </a>
                    </li>
                </ul>
            </li>


            <li class="nav-item">
                <a class="nav-link {{ request()->is('COMPLETAR*') ? 'active-nav-link' : '' }}" href="#">
                    <i class="bi bi-clock"></i>
                    Solicitudes pendientes
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('COMPLETAR*') ? 'active-nav-link' : '' }}" href="#">
                    <i class="bi bi-person"></i>
                    Usuarios de secretaría
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#academicoSubmenu" role="button" aria-expanded="{{ request()->is('teachers*') || request()->is('careers*') || request()->is('departments*') ? 'true' : 'false' }}" aria-controls="academicoSubmenu">
                    <i class="bi bi-book"></i>
                    Gestión Académica
                </a>
                <ul id="academicoSubmenu" class="collapse nav flex-column ms-3 {{ request()->is('teachers*') || request()->is('careers*') || request()->is('departments*') ? 'show' : '' }}">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('teachers*') ? 'active-nav-link' : '' }}" href="{{ route('teachers.index') }}">
                            <i class="bi bi-people"></i>
                            Docentes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('careers*') ? 'active-nav-link' : '' }}" href="{{ route('careers.index') }}">
                            <i class="bi bi-journal"></i>
                            Carreras
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('departments*') ? 'active-nav-link' : '' }}" href="{{ route('departments.index') }}">
                            <i class="bi bi-journal"></i>
                            Departamentos
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('companies*') ? 'active-nav-link' : '' }}" href="{{ route('companies.index') }}">
                    <i class="bi bi-building"></i>
                    Empresas
                </a>
            </li>

          
               <li class="nav-item">
    <a class="nav-link {{ request()->is('students*') ? 'active-nav-link' : '' }}" href="{{ route('students.index') }}">
        <i class="bi bi-mortarboard"></i>
        Alumnos
    </a>
</li>

        </ul>
    </div>
</nav>