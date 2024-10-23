<!-- resources/views/companies/create.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')

<div class="row">
    <div class="col-12">
        <div>
            <h4>Agregar ciudad nueva</h4>
        </div>

    </div>

</div>

<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <form method="POST" action=" {{route('cities.store')}} " id="" role="form">
                    @csrf

                    <input type="hidden" name="_token" value="" autocomplete="off">

                    <div class="form-group mb-3">
                        <label class="form-label required">
                            <label for="name" class="required">Ciudad</label>
                        </label>
                        <div>
                            <input class="form-control" placeholder="Ciudad a ingresar..." name="name" type="text" id="name">
                            <small class="form-hint">Ciudad <b>nombre</b></small>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="province_id">Provincia</label></label>
                    <div>
                        <select class=" form-control" name="province_id" id="">
                            @foreach ($provinces as $province)
                                <option value="{{$province->id}}">{{$province->name}}</option>
                            @endforeach
                        </select>


                    <small class="form-hint">Seleccione la <b>provincia</b>  a la cual pertenece la ciudad.</small>
                    </div>
                    </div>

                    <div class="form-footer">
                        <div class="text-end">
                            <div class="d-flex">
                                <a href="{{route('companies.create')}}" class="btn btn-danger m-2">Cancelar</a>
                                <div>
                                    <button type="submit" class="btn btn-primary ms-auto  m-2">Crear</button>
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

