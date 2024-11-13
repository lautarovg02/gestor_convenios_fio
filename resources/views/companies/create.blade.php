<!-- resources/views/companies/create.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')


<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center ps-4 pe-4">
            <h4>Agregar nueva empresa</h4>
            <a href="{{route('companies.index')}}" class="btn btn-secondary m-2">Volver</a>
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
                    {{Session::get('success')}}
                </div>
            @endif

            <!-- Mensajes flash de error-->
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif



            <div class="card-body">
                <form method="POST" action=" {{route('companies.store')}} " id="" role="form">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <label for="denomination" class="required-field fs-6">Denominación</label>
                        </label>
                        <div>
                            <input class="form-control fs-6" placeholder="Denominación" name="denomination" type="text" id="denomination">
                        </div>
                        @error('denomination')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label  fs-6 required-field">
                            <label for="cuit">Cuit</label>
                        </label>
                        <div>
                            <input class="form-control fs-6" placeholder="Cuit" name="cuit" type="text" id="cuit">
                            <small class="form-hint">Ingresar <b>CUIT</b> sin guiones.</small>
                            @error('cuit')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fs-6">
                            <label for="company_name">Nombre de la empresa</label>
                        </label>
                        <div>
                            <input class="form-control" placeholder="Nombre de la empresa" name="company_name" type="text" id="company_name">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fs-6">
                            <label for="sector">Sector</label>
                        </label>
                        <div>
                            <input class="form-control" placeholder="Sector" name="sector" type="text" id="sector">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fs-6">
                            <label for="entity">Entidad</label>
                        </label>
                        <div>
                            <input class="form-control" placeholder="Entidad" name="entity" type="text" id="entity">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fs-6">   <label for="company_category">Categoría</label></label>
                        <div>
                            <input class="form-control" placeholder="Categoría" name="company_category" type="text" id="company_category">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fs-6">   <label for="scope">Ámbito</label></label>
                        <div>
                            <input class="form-control" placeholder="Ámbito" name="scope" type="text" id="scope">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fs-6">
                            <label for="street">Calle</label>
                        </label>
                        <div>
                        <input class="form-control" placeholder="Street" name="street" type="text" id="street">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fs-6">
                            <label for="number">Número</label>
                        </label>
                        <div>
                            <input class="form-control" placeholder="Number" name="number" type="text" id="number">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fs-6 required-field">
                            <label for="city_id">Ciudad</label>
                        </label>
                        <div>
                            <select class=" form-control" name="city_id" id="">
                                    <option value="">Seleccionar</option>
                                @foreach ($cities as $city)
                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach
                            </select>
                            <small class="form-hint">Si no encuentra la <b>ciudad</b> en la lista, ingresarla en  <a href="{{route('cities.create')}}">Agregar Ciudad.</a></small>
                            @error('city_id')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="text-end">
                            <div class="d-flex">
                                <a href="{{route('companies.index')}}" class="btn btn-danger m-2">Cancelar</a>
                                <div>
                                    <button type="submit" class="btn btn-primary ms-auto m-2">Crear</button>
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
