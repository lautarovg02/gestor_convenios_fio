<form action="{{ route('teachers.index') }}" method="GET" class="row g-3 mb-4 mt-0 justify-content-end">
    <div class=" d-flex col-md-6 mt-0">
        <div class="me-2 flex-grow-1 mt-0">
            <select class="form-select font-size" id="career" name="career">
                <option value="">Carrera</option>
                <!-- Agregar opciones de Carreras dinámicamente -->
                @foreach($careers as $career)
                    <option class="font-size" value="{{ $career->id }}" {{ request('career') == $career->id ? 'selected' : '' }}>
                        {{ $career->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="me-2 flex-grow-1 mt-0">
            <select class="form-select font-size" id="role" name="role">
                <option value="">Roles</option>
                <!-- Agregar opciones de ciudades dinámicamente -->
                @foreach($roles as $role)
                    <option value="{{$role}}" {{ request('role') == $role ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4 d-flex align-items-end mt-0">
        <button type="submit" class="btn btn-primary mt-0" data-bs-target="#modal-loading">Filtrar</button>
        <a href="{{ route('teachers.index') }}" class="btn btn-secondary ms-2">Limpiar</a>
    </div>
</form>
