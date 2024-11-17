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

                    <div class="form-group mb-3">
                        <label class="form-label required-field fs-6" for='denomination'> Denominación</label>
                        <div>
                            <input class="form-control" name="denomination" id="denomination" type="text" value="{{$company->denomination}}" placeholder="Ingrese la denominación " autocomplete="off">
                            @error('denomination')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

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
                    <div class="form-group mb-3">
                        <label class="form-label" for= "company_name">Nombre de la empresa</label>
                        <div>
                            <input class="form-control" name="company_name" id="company_name" type="text" value="{{$company->company_name}}" placeholder="Ingrese el nombre de la empresa " autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label required" for= "sector">Sector</label>
                        <div>
                            <input class="form-control" name="sector" id="sector" type="text" value="{{$company->sector}}" placeholder="Ingrese el sector al que pertenece la empresa " autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label required" for= "entity">Entidad</label>
                        <div>
                            <input class="form-control" name="entity" id="entity" type="text" value="{{$company->entity}}" placeholder="Ingrese la entidad a la que pertenece la empresa " autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label required" for= "company_category">Categoría</label>
                        <div>
                            <input class="form-control" name="company_category" id="company_category" type="text" value="{{$company->company_category}}" placeholder="Ingrese la categoría de la empresa " autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label required" for= "scope">Ámbito</label>
                        <div>
                            <input class="form-control" name="scope" id="scope" type="text" value="{{$company->scope}}" placeholder="Ingrese el ámbito de la empresa " autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <p>Dirección</p>
                        <label class="form-label required" for= "street">Calle</label>
                        <div>
                            <input class="form-control" name="street" id="street" type="text" value="{{$company->street}}" placeholder="Calle " autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label required" for= "number">Número</label>
                        <div>
                            <input class="form-control" name="number" id="number" type="text" value="{{$company->number}}" placeholder="Ingrese el ámbito de la empresa " autocomplete="off">
                        </div>
                    </div>
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
