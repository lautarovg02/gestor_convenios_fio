<!-- resources/views/companies/create.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center ps-4 pe-4">
            <h4>Agregar nueva empresa</h4>
            <a href="{{ route('companies.index') }}" class="btn btn-secondary m-2">Volver</a>
        </div>

    </div>

    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles Empresas</h3>
                </div>

                <!-- Mensajes flash de success-->
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif

                <!-- Mensajes flash de error-->
                @if ($errors->has('error'))
                    <div class="alert alert-danger">
                        {{ $errors->first('error') }}
                    </div>
                @endif

                <div class="card-body">
                    <form method="POST" action=" {{ route('companies.store') }} " id="" role="form">
                        @csrf
                        <!-- Campo denominación -->
                        <div class="form-group mb-3">
                            <label class="form-label">
                                <label for="denomination" class="required-field fs-6">Denominación</label>
                            </label>
                            <div>
                                <input class="form-control fs-6" maxlength="40" placeholder="Denominación"
                                    name="denomination" type="text" id="denomination" value="{{ old('denomination') }}">
                            </div>
                            @error('denomination')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Campo cuit -->
                        <div class="form-group mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="form-label fs-6 required-field">
                                    <label for="cuit">CUIT</label>
                                </label>
                                <a href="https://seti.afip.gob.ar/padron-puc-constancia-internet/ConsultaConstanciaAction.do"
                                    target="_blank" class="text-info text-decoration-underline fs-6">
                                    Validar CUIT
                                </a>
                            </div>
                            <div>
                                <input class="form-control fs-6" maxlength="11" placeholder="CUIT" name="cuit"
                                    type="number" id="cuit" value="{{ old('cuit') }}"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                <small class="form-hint">Ingresar <b>CUIT</b> sin guiones.</small>
                                @error('cuit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo nombre de la empresa -->
                        <div class="form-group mb-3">
                            <label class="form-label fs-6">
                                <label for="company_name">Nombre de la empresa</label>
                            </label>
                            <div>
                                <input class="form-control" maxlength="100" placeholder="Nombre de la empresa"
                                    name="company_name" type="text" id="company_name">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label fs-6">
                                <label for="sector">Sector</label>
                            </label>
                            <div>
                                <input class="form-control" maxlength="40" placeholder="Sector" name="sector"
                                    type="text" id="sector" value="{{ old('sector') }}">
                            </div>
                        </div>
                        <!-- Selector de entidad con opción "Otros" -->
                        <div class="form-group mb-3">
                            <label class="form-label fs-6">
                                <label for="entity">Entidad</label>
                            </label>
                            <select name="entity" id="entity" class="form-select">
                                <option value="">Seleccionar</option>
                                @foreach ($entityTypes as $type)
                                    <option value="{{ $type->name }}"
                                        {{ old('entity_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                                <option value="other">Otro tipo</option>
                            </select>
                            <!-- Campo de texto oculto para ingresar otra opción -->
                            <div id="otherEntityInputWrapper" class="mt-2" style="display:none">
                                <label for="other_entity_input">Especificar otra opción:</label>
                                <input type="text" id="other_entity_input" name="other_entity_input" style="display:none"
                                    placeholder="Nueva entidad">
                                <input type="hidden" name="other_entity" id="other_entity">
                            </div>
                        </div>
                        <!-- Campo rubro -->
                        <div class="form-group mb-3">
                            <label class="form-label fs-6"> <label for="company_category">Categoría</label></label>
                            <div>
                                <input class="form-control" maxlength="20" placeholder="Categoría" name="company_category"
                                    type="text" id="company_category" value="{{ old('company_category') }}">
                            </div>
                        </div>
                        <!-- Campo Ámbito con varias opciones -->
                        <div class="form-group mb-3">
                            <label class="form-label fs-6" for="scope">Ámbito</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div>
                                    <input class="form-check-input" type="radio" id="scope_1" name="scope"
                                        value="Nacional" {{ old('scope', 'NACIONAL') == 'NACIONAL' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="scope_1">
                                        Nacional
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" id="scope_2" name="scope"
                                        value="Internacional" {{ old('scope') == 'INTERNACIONAL' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="scope_2">
                                        Internacional
                                    </label>
                                </div>
                            </div>
                        </div>

                        <h6>Dirección:</h6>
                        <div class="row align-items-end">
                            <!-- Campo nombre de la calle -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fs-6" for="street">Calle</label>
                                <input class="form-control" maxlength="100" placeholder="Calle" name="street"
                                    type="text" id="street" value="{{ old('street') }}">
                            </div>

                            <!-- Campo número -->
                            <div class="col-md-2 mb-3">
                                <label class="form-label fs-6" for="number">Número</label>
                                <input class="form-control" placeholder="Número" name="number" type="number"
                                    id="number" value="{{ old('number') }}">
                            </div>

                            <!-- Campo nombre de la ciudad -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label fs-6 required-field" for="city_id">Ciudad</label>
                                <select class="form-control" name="city_id" id="city_id">
                                    <option value="">Seleccionar</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-hint text-end d-block">
                                Si no encuentra la <b>ciudad</b> en la lista, ingresarla en
                                <a href="{{ route('cities.create') }}">Agregar Ciudad.</a>
                            </small>
                        </div>

                        <!-- Botones -->
                        <div class="form-footer">
                            <div class="text-end">
                                <div class="d-flex">
                                    <a href="{{ route('companies.index') }}" class="btn btn-danger m-2">Cancelar</a>
                                    <div>
                                        <button type="submit" class="btn btn-success ms-auto m-2">Crear</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
