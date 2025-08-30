@extends('layouts.app')

@section('content')
<div class="row row-deck row-cards content-with-footer-buffer">
    <div class="col-12">
        <div class="card shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                <h3 class="card-title">Editar empresa</h3>
                <nav aria-label="breadcrumb" class="ms-3 mt-3">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('companies.index') }}">Empresas</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('companies.show', $company) }}" class="text-muted">
                                {{ $company->company_name }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">
                            Editar
                        </li>
                    </ol>
                </nav>
                <a href="{{ route('companies.show', $company) }}" class="btn btn-outline-primary">← Volver</a>
            </div>

            <div class="card-body">
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif

                <form method="POST" action="{{ route('companies.update', $company) }}" role="form" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    @csrf
                    
                    <h4 class="mb-3 border-bottom pb-2">Datos Generales</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="denomination">Razón social</label>
                            <input class="form-control" name="denomination" id="denomination" type="text"
                                value="{{ old('denomination', $company->denomination) }}"
                                placeholder="Ingrese la Razón social" autocomplete="off">
                            @error('denomination')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="cuit">CUIT</label>
                            <input class="form-control" name="cuit" id="cuit" type="text"
                                value="{{ old('cuit', $company->cuit) }}"
                                placeholder="Ingrese el CUIT sin guiones" autocomplete="off">
                            <small class="form-hint">Ingresar <b>CUIT</b> sin guiones.</small>
                            @error('cuit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="company_name">Nombre de fantasía</label>
                            <input class="form-control" name="company_name" id="company_name" type="text"
                                value="{{ old('company_name', $company->company_name) }}"
                                placeholder="Ingrese el Nombre de fantasía" autocomplete="off">
                            @error('company_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="sector">Sector</label>
                            <input class="form-control" name="sector" id="sector" type="text"
                                value="{{ old('sector', $company->sector) }}"
                                placeholder="Ingrese el sector" autocomplete="off">
                            @error('sector')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Entidad</label>
                            <select name="entity_id" id="entity_id" class="form-select">
                                <option value="" disabled selected>Seleccionar</option>
                                @foreach ($entityTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('entity_id', $company->entity_id) == $type->id ? 'selected' : '' }}>
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
                                value="{{ old('company_category', $company->company_category) }}"
                                placeholder="Ingrese el rubro" autocomplete="off">
                            @error('company_category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">Ámbito</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="scope" id="scope_nacional"
                                        value="NACIONAL" {{ old('scope', $company->scope) == 'NACIONAL' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="scope_nacional">NACIONAL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="scope"
                                        id="scope_internacional" value="INTERNACIONAL"
                                        {{ old('scope', $company->scope) == 'INTERNACIONAL' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="scope_internacional">INTERNACIONAL</label>
                                </div>
                            </div>
                            @error('scope')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h4 class="mb-3 border-bottom pb-2">Dirección</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="street">Calle</label>
                            <input class="form-control" name="street" id="street" type="text"
                                value="{{ old('street', $company->street) }}"
                                placeholder="Ingrese la calle" autocomplete="off">
                            @error('street')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="number">Número</label>
                            <input class="form-control" name="number" id="number" type="text"
                                value="{{ old('number', $company->number) }}"
                                placeholder="Ingrese el número" autocomplete="off">
                            @error('number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field" for="city_id">Ciudad</label>
                            <select name="city_id" id="city_id" class="form-select">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id', $company->city_id) == $city->id ? 'selected' : '' }}>
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
                        {{-- Certificado AFIP --}}
                        <div class="col-md-6 mb-3">
                            <label for="afip_certificate" class="form-label">Certificado AFIP</label>
                            @if($company->url_certificate_afip)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $company->url_certificate_afip) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Ver archivo actual</a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="afip_certificate" name="afip_certificate">
                        </div>

                        {{-- Estatuto --}}
                        <div class="col-md-6 mb-3">
                            <label for="statute_confirmation" class="form-label">Estatuto</label>
                            @if($company->url_statute)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $company->url_statute) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Ver archivo actual</a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="statute_confirmation" name="statute_confirmation">
                        </div>

                        {{-- Designación de autoridades --}}
                        <div class="col-md-6 mb-3">
                            <label for="authorities_assignment" class="form-label">Designación de autoridades</label>
                            @if($company->url_assignment_authorities)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $company->url_assignment_authorities) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Ver archivo actual</a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="authorities_assignment" name="authorities_assignment">
                        </div>

                        {{-- Cláusula de confidencialidad --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="has_confidentiality_clause" name="has_confidentiality_clause" value="1" {{ $company->has_confidentiality_clause ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_confidentiality_clause">La empresa cuenta con cláusula de confidencialidad</label>
                            </div>
                            
                            {{-- Contenedor para mostrar/ocultar el campo de archivo --}}
                            <div id="confidentialityFileSection" style="display: {{ $company->has_confidentiality_clause ? 'block' : 'none' }};">
                                @if($company->url_confidentiality_clause_file)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $company->url_confidentiality_clause_file) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Ver archivo actual</a>
                                    </div>
                                @endif
                                <label for="confidentiality_clause_file" class="form-label mt-2">Adjuntar archivo</label>
                                <input type="file" class="form-control" id="confidentiality_clause_file" name="confidentiality_clause_file">
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="form-footer mt-4">
     <div class="text-end">
        <a href="{{ route('companies.show', $company) }}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" class="btn btn-success ms-auto">Guardar cambios</button>
    </div>
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