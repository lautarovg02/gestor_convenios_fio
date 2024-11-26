<!-- resources/views/companies/filters.blade.php -->
<form action="{{ route('companies.index') }}" method="GET" class="row g-3 mb-4 mt-3 justify-content-end">
    <div class=" d-flex col-md-6">
        <div class="me-2 flex-grow-1">
            <select class="form-select" id="city" name="city">
                <option value="">Ciudad</option>
                <!-- Agregar opciones de ciudades dinámicamente -->
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="me-2 flex-grow-1">
            <select class="form-select" id="sector" name="sector">
                <option value="">Sector</option>
                <!-- Agregar opciones de ciudades dinámicamente -->
                @foreach($sectors as $sector)
                    <option value="{{ $sector}}" {{ request('sector') == $sector ? 'selected' : '' }}>
                        {{ $sector }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="me-2 flex-grow-1">
            <select class="form-select" id="scope" name="scope">
                <option value="">Ámbito</option>
                <!-- Agregar opciones de ciudades dinámicamente -->
                @foreach($scopes as $scope)
                    <option value="{{ $scope }}" {{ request('scope') == $scope ? 'selected' : '' }}>
                        {{ $scope }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('companies.index') }}" class="btn btn-secondary ms-2">Limpiar</a>
    </div>
</form>
