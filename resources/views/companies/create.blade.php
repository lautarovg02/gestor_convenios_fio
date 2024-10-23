<!-- resources/views/companies/create.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')

<div class="row">
    <div class="col-12">
        <div>
            <h4>Agregar nueva empresa</h4>
        </div>

    </div>

</div>

<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalles Empresas</h3>
            </div>
            <div class="card-body">
                <form method="POST" action=" {{route('companies.store')}} " id="" role="form">
                    @csrf

                    <input type="hidden" name="_token" value="" autocomplete="off">

                    <div class="form-group mb-3">
                        <label class="form-label required">
                            <label for="denomination" class="required">Denominación</label>
                        </label>
                        <div>
                            <input class="form-control" placeholder="Denomination" name="denomination" type="text" id="denomination">
                            <small class="form-hint">company <b>denomination</b> instruction.</small>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="cuit">Cuit</label></label>
                    <div>
                    <input class="form-control" placeholder="Cuit" name="cuit" type="text" id="cuit">

                    <small class="form-hint">company <b>cuit</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="company_name">Nombre de la compañía</label></label>
                    <div>
                    <input class="form-control" placeholder="Company Name" name="company_name" type="text" id="company_name">

                    <small class="form-hint">company <b>company_name</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="sector">Sector</label></label>
                    <div>
                    <input class="form-control" placeholder="Sector" name="sector" type="text" id="sector">

                    <small class="form-hint">company <b>sector</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="entity">Entidad</label></label>
                    <div>
                    <input class="form-control" placeholder="Entity" name="entity" type="text" id="entity">

                    <small class="form-hint">company <b>entity</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="company_category">Categoría de compañía</label></label>
                    <div>
                    <input class="form-control" placeholder="Company Category" name="company_category" type="text" id="company_category">

                    <small class="form-hint">company <b>company_category</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="scope">Ámbito</label></label>
                    <div>
                    <input class="form-control" placeholder="Scope" name="scope" type="text" id="scope">

                    <small class="form-hint">company <b>scope</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="street">Calle</label></label>
                    <div>
                    <input class="form-control" placeholder="Street" name="street" type="text" id="street">

                    <small class="form-hint">company <b>street</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="number">Número</label></label>
                    <div>
                    <input class="form-control" placeholder="Number" name="number" type="text" id="number">

                    <small class="form-hint">company <b>number</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="city_id">Ciudad</label></label>
                    <div>
                        <select class=" form-control" name="city_id" id="">
                            @foreach ($cities as $city)
                                <option value="{{$city->id}}">{{$city->name}}</option>
                            @endforeach
                        </select>


                    <small class="form-hint">Si no encuentra la <b>ciudad</b> en la lista, ingresarla en  <a href="{{route('cities.create')}}">Agregar Ciudad.</a></small>
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
