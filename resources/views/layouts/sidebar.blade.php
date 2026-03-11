@vite('resources/css/sidebar.css')

<nav class="col-md-2 d-none d-md-block sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="btnHome nav-link {{ request()->routeIs('home.index') ? 'active' : '' }}"
                    href="{{ route('home.index') }}">
                    <i class="iconHome bi bi-house-door"></i>
                    Inicio
                </a>
            </li>

            @canany(['ver convenios', 'crud solicitudes'])
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#convenioSubmenu" role="button"
                    aria-expanded="{{ request()->is('agreements*') ? 'true' : 'false' }}"
                    aria-controls="convenioSubmenu">
                    <i class="bi bi-file-earmark-text"></i>
                    Convenios
                </a>
                <ul id="convenioSubmenu"
                    class="collapse nav flex-column ms-3 {{ request()->is('agreements*') ? 'show' : '' }}">
                    @canany(['ver convenios'])
                        <li class=" containerAllConvenios nav-item">
                            <a class="nav-link {{ request()->is('agreements') ? 'active-nav-link' : '' }}"
                                href="{{ route('agreements.index') }}">
                                <i class="bi bi-list-ul"></i>
                                Todos los convenios
                            </a>
                        </li>
                    @endcanany
                    @canany(['crud solicitudes', 'crear solicitud convenio'])
                        <li class=" licontainer nav-item">
                            <a class="btnAddConvenio" href="#" data-bs-toggle="modal"
                                data-bs-target="#crearConvenioModal">
                                <i class=" iconAdd bi bi-plus-circle"></i>
                                Crear nuevo convenio
                            </a>
                        </li>
                    @endcanany
                </ul>
            </li>
            @endcanany

            @canany(['ver solicitudes'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('pending-requests*') ? 'active-nav-link' : '' }}" href="{{ route('pending-requests.index') }}">
                        <i class="bi bi-clock"></i>
                        Solicitudes pendientes
                    </a>
                </li>
            @endcanany
            @canany(['ver rechazadas'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('approved-requests*') ? 'active-nav-link' : '' }}" href="{{ route('approved-requests.index') }}">
                        <i class="bi bi-check-circle"></i>
                        Solicitudes aprobadas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('rejected-requests*') ? 'active-nav-link' : '' }}" href="{{ route('rejected-requests.index') }}">
                        <i class="bi bi-x-circle"></i>
                        Solicitudes rechazadas
                    </a>
                </li>
            @endcanany
            @canany(['ver usuarios'])
                <li class="nav-item">

                    <a class="nav-link {{ request()->is('adminUsers*') ? 'active-nav-link' : '' }}"
                        href="{{ route('adminUsers.index') }}">
                        <i class="bi bi-person"></i>
                        Administrar usuarios
                    </a>
                </li>
            @endcanany

            @canany(['ver docentes', 'ver docentes', 'ver carreras', 'ver departamentos'])
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#academicoSubmenu" role="button"
                    aria-expanded="{{ request()->is('teachers*') || request()->is('careers*') || request()->is('departments*') ? 'true' : 'false' }}"
                    aria-controls="academicoSubmenu">
                    <i class="bi bi-book"></i>
                    Gestión Académica
                </a>
                <ul id="academicoSubmenu"
                    class="collapse nav flex-column ms-3 {{ request()->is('teachers*') || request()->is('careers*') || request()->is('departments*') ? 'show' : '' }}">
                    @canany(['ver docentes'])
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('teachers*') ? 'active-nav-link' : '' }}"
                                href="{{ route('teachers.index') }}">
                                <i class="bi bi-people"></i>
                                Docentes
                            </a>
                        </li>
                    @endcanany
                    @canany(['ver carreras'])
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('careers*') ? 'active-nav-link' : '' }}"
                                href="{{ route('careers.index') }}">
                                <i class="bi bi-journal"></i>
                                Carreras
                            </a>
                        </li>
                    @endcanany
                    @canany(['ver departamentos'])
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('departments*') ? 'active-nav-link' : '' }}"
                                href="{{ route('departments.index') }}">
                                <i class="bi bi-journal"></i>
                                Departamentos
                            </a>
                        </li>
                    @endcanany
                </ul>
            </li>
            @endcanany

            @canany(['ver empresas'])
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#empresaSubmenu" role="button"
                    aria-expanded="{{ request()->is('companies*') ? 'true' : 'false' }}"
                    aria-controls="empresaSubmenu">
                    <i class="bi bi-building"></i>
                    Empresas
                </a>
                <ul id="empresaSubmenu"
                    class="collapse nav flex-column ms-3 {{ request()->is('companies*') ? 'show' : '' }}">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('companies.index') ? 'active-nav-link' : '' }}"
                            href="{{ route('companies.index') }}">
                            <i class="bi bi-list-ul"></i>
                            Ver empresas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('companies.create') ? 'active-nav-link' : '' }}"
                            href="{{ route('companies.create') }}">
                            <i class="bi bi-plus-circle"></i>
                            Agregar empresa
                        </a>
                    </li>
                </ul>
            </li>
            @endcanany

            @canany(['ver alumnos'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('students*') ? 'active-nav-link' : '' }}"
                        href="{{ route('students.index') }}">
                        <i class="bi bi-mortarboard"></i>
                        Alumnos
                    </a>
                </li>
            @endcanany
        </ul>
    </div>
</nav>
