 <!-- Filtro y Búsqueda -->
 <div class="card shadow-sm rounded mb-4 p-3">
     <div class="row g-3 align-items-end">

         {{-- Filtro por Estado --}}
         <div class="col-md-3">
             <label for="status" class="form-label">Estado</label>
             <select id="filter-status" class="form-select">
                 <option value="">Todos los estados</option>
                 @foreach ($statuses as $s)
                     <option value="{{ $s->status }}" {{ request('status') == $s->status ? 'selected' : '' }}>
                         {{ $s->status }}
                     </option>
                 @endforeach
             </select>
         </div>

         {{-- Filtro por Tipo de Convenio --}}
         <div class="col-md-3">
             <label for="filter-type" class="form-label">Tipo de Convenio</label>
             <select id="filter-type" class="form-select">
                 <option value="">Todos los tipos</option>
                 @foreach ($typeFrameworkAgreement as $t)
                     <option value="{{ $t->id }}" {{ request('type') == $t->id ? 'selected' : '' }}>
                         {{ $t->type }}
                     </option>
                 @endforeach
             </select>
         </div>

         {{-- Búsqueda por Palabra Clave --}}
         <div class="col-md-4">
             <label for="filter-search" class="form-label">Buscar por empresa</label>
             <input type="text" id="filter-search" class="form-control"
                 placeholder="Razón social, CUIT..." value="{{ request('search') }}">
         </div>

         {{-- Acciones --}}
         <div class="col-md-2 d-flex gap-2">
             <button class="btn btn-primary w-100" type="button" onclick="applyFilters()">
                 <i class="bi bi-search me-1"></i> Filtrar
             </button>
             <a href="{{ route('agreements.index') }}" class="btn btn-secondary w-100">
                 <i class="bi bi-x-lg"></i>
             </a>
         </div>

     </div>
 </div>

 <script>
 function applyFilters() {
     const type   = document.getElementById('filter-type').value;
     const status = document.getElementById('filter-status').value;
     const search = document.getElementById('filter-search').value;

     const params = new URLSearchParams();
     if (type)   params.set('type',   type);
     if (status) params.set('status', status);
     if (search) params.set('search', search);

     const query = params.toString();
     window.location.href = '{{ route('agreements.index') }}' + (query ? '?' + query : '');
 }
 </script>
