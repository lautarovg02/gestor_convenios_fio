<!-- resources/views/companies/filters.blade.php -->
<form action="{{ route('companies.index') }}" method="GET" class=" row g-3 mb-1 mt- align-items-center justify-content-end">
    <div class=" d-flex col-md-6 mt-0">
        <div class="me-2 flex-grow-1">
            <select class="form-select font-size" id="city" name="city">
                <option value="">Ciudad</option>
                <!-- Agregar opciones de ciudades dinámicamente -->
                @foreach($cities as $city)
                    <option class="font-size" value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="me-2 flex-grow-1">
            <select class="form-select font-size" id="sector" name="sector">
                <option value="">Sector</option>
                <!-- Agregar opciones de ciudades dinámicamente -->
                @foreach($sectors as $sector)
                    <option class="font-size" value="{{ $sector}}" {{ request('sector') == $sector ? 'selected' : '' }}>
                        {{ $sector }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="me-2 flex-grow-1 mt-0">
            <select class="form-select font-size" id="scope" name="scope">
                <option value="">Ámbito</option>
                <!-- Agregar opciones de ciudades dinámicamente -->
                @foreach($scopes as $scope)
                    <option class="font-size" value="{{ $scope }}" {{ request('scope') == $scope ? 'selected' : '' }}>
                        {{ $scope }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modal-loading">Filtrar</button>
        <a href="{{ route('companies.index') }}" class="btn btn-secondary ms-2">Limpiar</a>
    </div>
</form>
