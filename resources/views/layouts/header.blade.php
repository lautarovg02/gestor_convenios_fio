@vite('resources\css\header.css')

<header class="bg-white shadow-sm py-2 border-bottom fixed-top" style="z-index: 1050;">
    <div class=" containerHeader container-fluid d-flex justify-content-between align-items-center px-4">

        <div class=" d-flex align-items-center gap-3">
            <a href="{{ route('home.index') }}">
            <img class="imgFacultad" src="{{ asset('images/image5.png') }}" alt="Logo Facultad"">
            </a>
            <span class=" spanGestor fw-semibold text-secondary d-none d-md-inline">Gestor de convenios</span>
        </div>

        <!-- Bloque de usuario -->
        <div class=" containerIconLogoutAndText d-flex align-items-center gap-3">
            
            <!-- Datos del usuario -->
            <div class="text-end">
                <div class="fw-semibold">{{ Auth::user()->name }}</div>
                <small class="text-muted">{{ Auth::user()->role->name }}</small>
            </div>
            <!--Cuando tengamos sesiones hay que mostrar el nombre del usuario y no hardcodear -->
            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <div class="containerIconLogout">
                    <button type="submit" class="btn btn-link text-dark p-0 m-0 ms-3" style="text-decoration: none;">
                        <i class=" iconLogout bi bi-box-arrow-right fs-4"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</header>
