<form action="{{ route('companies.index') }}" method="GET" class="p-3 rounded border shadow-sm bg-white">
    <div class="row g-3 align-items-end">

        <!-- Ciudad -->
        <div class="col-md-3">
            <label for="city" class="form-label">Ciudad</label>
            <select class="form-select font-size" id="city" name="city">
                <option value="">Todas</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Sector -->
        <div class="col-md-3">
            <label for="sector" class="form-label">Sector</label>
            <select class="form-select font-size" id="sector" name="sector">
                <option value="">Todos</option>
                @foreach($sectors as $sector)
                    <option value="{{ $sector }}" {{ request('sector') == $sector ? 'selected' : '' }}>
                        {{ $sector }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Ámbito -->
        <div class="col-md-3">
            <label for="scope" class="form-label">Ámbito</label>
            <select class="form-select font-size" id="scope" name="scope">
                <option value="">Todos</option>
                @foreach($scopes as $scope)
                    <option value="{{ $scope }}" {{ request('scope') == $scope ? 'selected' : '' }}>
                        {{ $scope }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Buscador -->
        <div class="col-md-3">
            <label for="search" class="form-label">Buscar</label>
            <div class="input-group">
                <input type="text" id="search" name="search" class="font-size form-control"
                       placeholder="Buscar" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="{{ route('companies.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>

    </div>
</form>
