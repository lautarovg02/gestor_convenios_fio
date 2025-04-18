<!-- Filtros y Búsqueda -->
<div class="card shadow-sm rounded mb-4 p-3">
    <form action="{{ route('teachers.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="career" class="form-label">Carrera</label>
            <select class="form-select" name="career" id="career">
                <option value="">Todas</option>
                @foreach ($careers as $career)
                    <option value="{{ $career->id }}" {{ request('career') == $career->id ? 'selected' : '' }}>
                        {{ $career->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="role" class="form-label">Rol</label>
            <select class="form-select" name="role" id="role">
                <option value="">Todos</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="search" class="form-label">Buscar</label>
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control"
                    placeholder="Buscar docentes..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Buscar</button>
                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>
</div>
