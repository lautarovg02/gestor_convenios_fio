<header class="bg-white shadow-sm py-2 border-bottom fixed-top" style="z-index: 1050;">
  <div class="container-fluid d-flex justify-content-between align-items-center px-4">
    
    <div class="d-flex align-items-center gap-3">
      <img src="{{ asset('images/image5.png') }}" alt="Logo Facultad" style="height: 40px;">
      <span class="fw-semibold text-secondary d-none d-md-inline">Facultad de Ingeniería</span>
    </div>
    
    <!-- Bloque de usuario -->
    <div class="d-flex align-items-center gap-3">
      <!-- Icono de usuario -->
      <i class="bi bi-person-circle fs-4"></i>
      
      <!-- Datos del usuario -->
      <div class="text-end">
        <div class="fw-semibold">Sec. de extensión</div>
        <small class="text-muted">Secretaria</small>
      </div>
      <!--Cuando tengamos sesiones hay que mostrar el nombre del usuario y no hardcodear -->
      <!-- Logout -->
      <a href="#" class="text-dark ms-3" onclick="event.preventDefault();">
        <i class="bi bi-box-arrow-right fs-4"></i>
      </a>
    </div>
  </div>
</header>
