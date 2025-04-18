 <!-- Filtro y Búsqueda -->
 <div class="card shadow-sm rounded mb-4 p-3">
     <form action="{{ route('careers.index') }}" method="GET" class="row g-3 align-items-end">
         <div class="col-md-4">
             <label for="department" class="form-label">Departamento</label>
             <select name="department" id="department" class="form-select">
                 <option value="">Todos</option>
                 @foreach ($departments as $dept)
                     <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                         {{ $dept->name }}
                     </option>
                 @endforeach
             </select>
         </div>
         <div class="col-md-8">
             <label for="search" class="form-label">Buscar</label>
             <div class="input-group">
                 <input type="text" name="search" class="form-control" id="search"
                     placeholder="Buscar carreras..." value="{{ request('search') }}">
                 <button class="btn btn-primary" type="submit">Buscar</button>
                 <a href="{{ route('careers.index') }}" class="btn btn-secondary">Limpiar</a>
             </div>
         </div>
     </form>
 </div>
