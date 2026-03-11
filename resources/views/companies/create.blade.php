<!-- resources/views/companies/create.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')

@section('content')
    <div class="container mt-4">

        <!-- Header con Título y Breadcrumb (MISMO DISEÑO QUE DOCENTES) -->
        <div class="d-flex justify-content-between align-items-center mb-2" style="    padding: 0 6%;">
            <div>
                
                <h4 class="fw-bold mb-1">Agregar empresa</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item text-muted">Empresas</li>
                        <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Agregar empresa</li>
                    </ol>
                </nav>
            
            </div>
            <a href="{{ route('companies.index') }}" class="btn btn-outline-primary">
                ← Volver
            </a>
        </div>

        <div style="display: flex; justify-content: center;">
            <!-- Mensajes flash de success-->
            @if (Session::has('success'))
                <div class="alert alert-success" style="width: fit-content;">
                    {{ Session::get('success') }}
                </div>
            @endif

            <!-- Mensajes flash de error-->
            @if ($errors->has('error'))
                <div class="alert alert-danger" style="width: fit-content;">
                    {{ $errors->first('error') }}
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-center">

            <div class="w-50 card shadow-sm rounded">

                <div class="card-header bg-light" style="padding: 2%; margin-bottom: 4%;">
                    <h4 class="mb-0 fw-bold">Datos de la empresa</h4>
                </div>

                <div class="card-body">
                    <form class="d-flex flex-column align-items-center" method="POST"
                        action=" {{ route('companies.store') }} " id="" role="form">
                        @csrf
                        <!-- Campo Razón social -->
                        <div class="w-75 form-group mb-3">
                            <label class="form-label">
                                <label for="denomination" class="required-field fs-6">Razón social</label>
                            </label>
                            <div>
                                <input class="form-control fs-6" maxlength="40" placeholder="Razón social"
                                    name="denomination" type="text" id="denomination" value="{{ old('denomination') }}">
                            </div>
                            @error('denomination')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Campo cuit -->
                        <div class="w-75 form-group mb-3">
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
                        <div class="w-75 form-group mb-3">
                            <label class="form-label fs-6 required-field">
                                <label for="company_name">Nombre de fantasía</label>
                            </label>
                            <div>
                                <input class="form-control" maxlength="100" placeholder="Nombre de fantasía"
                                    name="company_name" type="text" id="company_name">
                            </div>
                        </div>
                        <div class="w-75 form-group mb-3">
                            <label class="form-label fs-6 required-field">
                                <label for="sector">Sector</label>
                            </label>
                            <select class="form-select" name="sector" id="sector">
                                <option value="">Seleccionar sector</option>
                                <option value="Público" {{ old('sector') == 'Público' ? 'selected' : '' }}>Público</option>
                                <option value="Privado" {{ old('sector') == 'Privado' ? 'selected' : '' }}>Privado</option>
                                <option value="Mixto" {{ old('sector') == 'Mixto' ? 'selected' : '' }}>Mixto</option>
                            </select>
                            @error('sector')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Selector de entidad con opción "Otros" -->
                        <div class="w-75 form-group mb-3">
                            <label class="form-label fs-6 required-field">
                                <label for="entity">Entidad</label>
                            </label>
                            <select name="entity" id="entity" class="form-select">
                                <option value="">Seleccionar</option>
                                @foreach ($entityTypes as $type)
                                    <option value="{{ $type->name }}"
                                        {{ old('entity_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                    </option>
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
                        <div class="w-75 form-group mb-3">
                            <label class="form-label fs-6"><label for="company_category">Categoría</label></label>
                            <select class="form-select" name="company_category" id="company_category">
                                <option value="">Seleccionar categoría</option>
                                <option value="Con fines de lucro" {{ old('company_category') == 'Con fines de lucro' ? 'selected' : '' }}>Con fines de lucro</option>
                                <option value="Sin fines de lucro" {{ old('company_category') == 'Sin fines de lucro' ? 'selected' : '' }}>Sin fines de lucro</option>
                                <option value="Gubernamental" {{ old('company_category') == 'Gubernamental' ? 'selected' : '' }}>Gubernamental</option>
                            </select>
                            @error('company_category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Campo Ámbito con varias opciones -->
                        <div class="w-75 form-group mb-3">
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

                        <!-- Campo Confidencialidad -->
                        <div class="w-75 form-group mb-3">
                            <label class="form-label fs-6" for="confidentiality">Cláusula de Confidencialidad</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div>
                                    <input class="form-check-input" type="radio" id="confidentiality_1" name="confidentiality"
                                        value="1" {{ old('confidentiality', '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="confidentiality_1">
                                        Sí
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" id="confidentiality_0" name="confidentiality"
                                        value="0" {{ old('confidentiality', '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="confidentiality_0">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>

                        <h3 class="w-100 pt-2 " style="border-top: 1px solid #ccc;">Dirección</h3>
                        <div class="justify-content-center row align-items-end">

                            <div class=""
                                style="display: flex; justify-content:center; align-items: center; gap: 30px;">

                                <!-- Campo nombre de la calle -->
                                <div class="w-36 col-md-6 mb-3" style="width: 32%;">
                                    <label class="form-label fs-6 required-field" for="street">Calle</label>
                                    <input class="form-control" maxlength="100" placeholder="Calle" name="street"
                                        type="text" id="street" value="{{ old('street') }}">
                                </div>

                                <!-- Campo número -->
                                <div class="col-md-2 mb-3" style="width: 32%;">
                                    <label class="form-label fs-6 required-field" for="number">Número</label>
                                    <input class="form-control" placeholder="Número" name="number" type="number"
                                        id="number" value="{{ old('number') }}">
                                </div>
                            </div>


                            <!-- Campo nombre de la ciudad -->
                            <div class=" col-md-4 mb-3" style="width: 71%;">
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

                                <small class="fs-10 form-hint text-end d-block" style="font-size: 10px;">
                                Si no encuentra la <b>ciudad</b> en la lista, ingresarla en
                                <a href="{{ route('cities.create') }}">Agregar Ciudad.</a>
                                </small>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-4 form-footer w-100">
                            <div class="text-end">
                                <div class="d-flex">
                                    <a href="{{ route('companies.index') }}" class="min-vw-50 btn btn-danger m-2"
                                        style="min-width: 100px; text-transform: capitalize;">Cancelar</a>

                                    <button type="submit" class="btn btn-success ms-auto m-2"
                                        style="min-width: 100px">Crear empresa</button>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
