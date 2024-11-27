<!-- resources/views/companies/edit.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')

<div class="row row-deck row-cards">
    <div class="col-12 ">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                <h3 class="card-title"> Detalles de la empresa</h3>
                <a href="{{route('companies.index')}}" class="btn btn-secondary m-2">Volver</a>
            </div>
            <div>
                <!-- Mensajes flash de success-->
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{Session::get('success')}}
                    </div>
                @endif

                <!-- Mensajes flash de erroo-->
                @if ($errors->has('error'))
                    <div class="alert alert-danger">
                        {{ $errors->first('error') }}
                    </div>
                @endif
            </div>

            <div class="card-body">
                <form method="POST"
                    action="{{ route('companies.update', $company) }}" id="" role="form" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    @csrf

                    {{-- edit form --}}

                    <!-- Campo Denominación -->
                    <div class="form-group mb-3">
                        <label class="form-label required-field fs-6" for='denomination'> Denominación</label>
                        <div>
                            <input class="form-control" name="denomination" id="denomination" type="text" value="{{$company->denomination}}" placeholder="Ingrese la denominación " autocomplete="off">
                            @error('denomination')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Campo CUIT -->
                    <div class="form-group mb-3">
                        <label class="form-label required-field" for= "cuit">CUIT</label>
                        <div>
                            <input class="form-control" name="cuit" id="cuit" type="text" value="{{$company->cuit}}" placeholder="Ingrese el CUIT de la empresa " autocomplete="off">
                            <small class="form-hint">Ingresar <b>CUIT</b> sin guiones.</small>
                        </div>
                        @error('cuit')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <!-- Campo nombre fantasía -->
                    <div class="form-group mb-3">
                        <label class="form-label" for= "company_name">Nombre de la empresa</label>
                        <div>
                            <input class="form-control" name="company_name" id="company_name" type="text" value="{{$company->company_name}}" placeholder="Ingrese el nombre de la empresa " autocomplete="off">
                        </div>
                    </div>
                    <!-- Campo Sector -->
                    <div class="form-group mb-3">
                        <label class="form-label required" for= "sector">Sector</label>
                        <div>
                            <input class="form-control" name="sector" id="sector" type="text" value="{{$company->sector}}" placeholder="Ingrese el sector al que pertenece la empresa " autocomplete="off">
                        </div>
                    </div>
                    <!-- Campo Entidad -->
                    <div class="form-group mb-3">
                        <label class="form-label fs-6" for="entity">Entidad</label>
                        <select name="entity" id="entity" class="form-select">
                            <option value="">Seleccionar</option>
                            @foreach ($entityTypes as $type)
                                <option value="{{ $type }}" {{ old('entity', $company->entity) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                            <option value="other" {{ old('entity', $company->entity) == 'other' ? 'selected' : '' }}>Otro tipo</option>
                        </select>


                        <!-- Campo de texto oculto para ingresar otra opción -->
                        <div id="otherEntityInputWrapper" class="mt-2" style="display: {{ old('entity', $company->entity) == 'other' ? 'block' : 'none' }};">
                            <label for="other_entity_input">Especificar otra opción:</label>
                            <input type="text" id="other_entity_input" name="other_entity_input"
                                    placeholder="Nueva entidad">
                            <input type="hidden" name="other_entity" id="other_entity">
                        </div>
                    </div>
                    <!-- Campo Rubro -->
                    <div class="form-group mb-3">
                        <label class="form-label required" for= "company_category">Rubro</label>
                        <div>
                            <input class="form-control" name="company_category" id="company_category" type="text" value="{{$company->company_category}}" placeholder="Ingrese la categoría de la empresa " autocomplete="off">
                        </div>
                    </div>
                    <!-- Campo Ámbito -->
                    <div class="form-group mb-3">
                        <label class="form-label required" for="scope">Ámbito</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input  class="form-check-input"  type="radio" name="scope"  id="scope_nacional"
                                    value="NACIONAL" {{ old('scope', $company->scope) === 'NACIONAL' ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="scope_nacional">NACIONAL</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="scope" id="scope_internacional"
                                    value="INTERNACIONAL" {{ old('scope', $company->scope) === 'INTERNACIONAL' ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="scope_internacional">INTERNACIONAL</label>
                            </div>
                        </div>
                        @error('scope')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <p>Dirección</p>
                    <!-- Campo Calle -->
                        <label class="form-label required" for= "street">Calle</label>
                        <div>
                            <input class="form-control" name="street" id="street" type="text" value="{{$company->street}}" placeholder="Calle " autocomplete="off">
                        </div>
                    </div>
                    <!-- Campo número -->
                    <div class="form-group mb-3">
                        <label class="form-label required" for= "number">Número</label>
                        <div>
                            <input class="form-control" name="number" id="number" type="text" value="{{$company->number}}" placeholder="Ingrese el ámbito de la empresa " autocomplete="off">
                        </div>
                    </div>
                    <!-- Campo Ciudad -->
                    <div class="form-group mb-3">
                        <label class="form-label required-field" for= "city_id">Ciudad</label>
                        <select name="city_id" id="" class="form-control required">
                            @foreach ($cities as $city)
                                <option value="{{$city->id}}"
                                    @if ($company->city_id === $city->id)
                                        selected
                                    @endif>
                                    {{$city->name}}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-hint">Si no encuentra la <b>ciudad</b> en la lista, ingresarla en  <a href="">Agregar Ciudad.</a></small>
                            @error('city_id')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                    </div>

                        <div class="form-footer">
                            <div class="text-end">
                                <div class="d-flex">
                                    <a href="{{route('companies.index')}}" class="btn btn-danger m-2">Cancelar</a>
                                    <button type="submit" class="btn btn-success ms-auto m-2">Guardar modificación</button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
