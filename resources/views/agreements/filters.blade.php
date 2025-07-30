 <!-- Filtro y Búsqueda -->
 <div class="card shadow-sm rounded mb-4 p-3">
     <form action="{{ route('agreements.index') }}" method="GET" class="row g-3 align-items-end d-flex wrap">
         <div class="col-md-3">
             <label for="type" class="form-label">Estado</label>
             <select name="type" id="type" class="form-select">
                 <option value="">Todos</option>
                 @foreach ($statuses as $status)
                     <option value="{{ $status->status }}" {{ request('status') == $status->status ? 'selected' : '' }}>
                         {{ $status->status }}
                     </option>
                 @endforeach
             </select>
         </div>
         <div class="col-md-3">
             <label for="type" class="form-label">Convenio</label>
             <select name="type" id="type" class="form-select">
                 <option value="">Todos</option>
                 @foreach ($typeFrameworkAgreement as $type)
                     <option value="{{ $type->type }}" {{ request('type') == $type->type ? 'selected' : '' }}>
                         {{ $type->type }}
                     </option>
                 @endforeach
             </select>
         </div>
         <div class="col-md-6">
             <label for="search" class="form-label">Buscar</label>
             <div class="input-group">
                 <input type="text" name="search" class="form-control g-3" id="search"
                     placeholder="Buscar convenios..." value="{{ request('search') }}">
                 <button class="btn btn-primary" type="submit">Buscar</button>
                 <a href="{{ route('careers.index') }}" class="btn btn-secondary">Limpiar</a>
             </div>
         </div>
     </form>
 </div>
