<form action="{{ route('students.index') }}" method="GET" class="p-3 rounded border shadow-sm bg-white mb-4">
    <div class="row g-3 align-items-end">

        <div class="col-md-3">
            <label for="career" class="form-label">Carrera</label>
            <select class="form-select font-size" id="career" name="career">
                <option value="">Todas</option>
                @if(isset($careers))
                    @foreach($careers as $career)
                        <option value="{{ $career->name }}" {{ request('career') == $career->name ? 'selected' : '' }}>
                            {{ $career->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="col-md-3">
            <label for="city" class="form-label">Ciudad</label>
            <input type="text" id="city" name="city" class="form-control font-size" 
                   placeholder="Ej: Olavarría" value="{{ request('city') }}">
        </div>

        <div class="col-md-6">
            <label for="search" class="form-label">Buscar</label>
            <div class="input-group">
                <input type="text" id="search" name="search" class="font-size form-control"
                       placeholder="Nombre, Apellido o DNI" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>

    </div>
</form>