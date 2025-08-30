<!-- resources/views/companies/create.blade.php -->
<!-- @extends('layouts.app') -->
@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Agregar nueva empresa</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('companies.index') }}">Empresas</a>
                        </li>
                        <li class="breadcrumb-item active fw-bold" aria-current="page">Crear Empresa</li>
                    </ol>
                </nav>
                <a href="{{ route('companies.index') }}" class="btn btn-outline-primary">
                    ← Volver
                </a>
            </div>

            <div class="card-body">
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif

                <form method="POST" action="{{ route('companies.store') }}" role="form" enctype="multipart/form-data">
                    @csrf

                    {{-- Sección: Datos Generales --}}
                    <h4 class="mb-3 border-bottom pb-2">Datos Generales</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="denomination">Razón social</label>
                            <input class="form-control" name="denomination" id="denomination" type="text"
                                value="{{ old('denomination') }}" placeholder="Ingrese la razón social" autocomplete="off">
                            @error('denomination')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="cuit">CUIT</label>
                            <input class="form-control" name="cuit" id="cuit" type="number"
                                value="{{ old('cuit') }}" placeholder="Ingrese el CUIT sin guiones" autocomplete="off">
                            <small class="form-hint">Ingresar <b>CUIT</b> sin guiones.</small>
                            @error('cuit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="company_name">Nombre de fantasía</label>
                            <input class="form-control" name="company_name" id="company_name" type="text"
                                value="{{ old('company_name') }}" placeholder="Ingrese el nombre de fantasía" autocomplete="off">
                            @error('company_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="sector">Sector</label>
                            <input class="form-control" name="sector" id="sector" type="text"
                                value="{{ old('sector') }}" placeholder="Ingrese el sector" autocomplete="off">
                            @error('sector')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="entity_id">Entidad</label>
                            <select name="entity_id" id="entity_id" class="form-select">
                                <option value="" disabled selected>Seleccionar</option>
                                @foreach ($entityTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('entity_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('entity_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="company_category">Rubro</label>
                            <input class="form-control" name="company_category" id="company_category" type="text"
                                value="{{ old('company_category') }}" placeholder="Ingrese el rubro" autocomplete="off">
                            @error('company_category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Ámbito</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="scope" id="scope_nacional"
                                        value="NACIONAL" {{ old('scope') == 'NACIONAL' || old('scope') == null ? 'checked' : '' }}>
                                    <label class="form-check-label" for="scope_nacional">Nacional</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="scope" id="scope_internacional"
                                        value="INTERNACIONAL" {{ old('scope') == 'INTERNACIONAL' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="scope_internacional">Internacional</label>
                                </div>
                            </div>
                            @error('scope')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Sección: Dirección --}}
                    <h4 class="mb-3 border-bottom pb-2">Dirección</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="street">Calle</label>
                            <input class="form-control" name="street" id="street" type="text"
                                value="{{ old('street') }}" placeholder="Ingrese la calle" autocomplete="off">
                            @error('street')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="number">Número</label>
                            <input class="form-control" name="number" id="number" type="text"
                                value="{{ old('number') }}" placeholder="Ingrese el número" autocomplete="off">
                            @error('number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="city_id">Ciudad</label>
                            <select name="city_id" id="city_id" class="form-select">
                                <option value="" disabled selected>Seleccionar</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                             <small class="form-hint">Si no encuentra la <b>ciudad</b> en la lista, puede agregarla en la sección <a href="{{ route('cities.create') }}">Agregar Ciudad.</a></small>
                            @error('city_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    {{-- Sección: Documentación --}}
                    <h4 class="mb-3 border-bottom pb-2">Documentación</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="afip_certificate">Certificado AFIP</label>
                            <input type="file" name="afip_certificate" class="form-control">
                            @error('afip_certificate')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="statute_confirmation">Estatuto</label>
                            <input type="file" name="statute_confirmation" class="form-control">
                            @error('statute_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="authorities_assignment">Designación de autoridades</label>
                            <input type="file" name="authorities_assignment" class="form-control">
                            @error('authorities_assignment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="has_confidentiality_clause" name="has_confidentiality_clause" value="1" {{ old('has_confidentiality_clause') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_confidentiality_clause">La empresa tiene una cláusula de confidencialidad</label>
                            </div>
                            <div id="confidentialityFileSection" style="display: {{ old('has_confidentiality_clause') ? 'block' : 'none' }};">
                                <label class="form-label" for="confidentiality_clause_file">Archivo de cláusula</label>
                                <input type="file" name="confidentiality_clause_file" id="confidentiality_clause_file" class="form-control">
                                @error('confidentiality_clause_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end border-0">
                        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Crear Empresa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Script para mostrar/ocultar el campo de archivo de confidencialidad
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('has_confidentiality_clause');
        const fileSection = document.getElementById('confidentialityFileSection');

        checkbox.addEventListener('change', function () {
            if (this.checked) {
                fileSection.style.display = 'block';
            } else {
                fileSection.style.display = 'none';
            }
        });
    });
</script>
@endsection