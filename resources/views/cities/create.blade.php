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
                    <div class="form-group mb-3 fs-6">
                        <label class="form-label required-field">
                            <label for="name" class="required-file">Ciudad</label>
                        </label>
                        <div>
                            <input class="form-control " maxlength="40" placeholder="Ciudad a ingresar..." name="name" type="text" id="name">
                            <small class="form-hint">Ciudad <b>nombre</b></small>
                            @error('name')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group mb-3 fs-6">
                    <label class="form-label required-field">   <label for="province_id">Provincia</label></label>
                    <div>
                        <select class=" form-control " name="province_id" id="">
                                <option value="" >Seleccionar</option>
                            @foreach ($provinces as $province)
                                <option value="{{$province->id}}">{{$province->name}}</option>
                            @endforeach
                        </select>
                        <small class="form-hint">Seleccione la <b>provincia</b>  a la cual pertenece la ciudad.</small>
                        @error('province_id')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
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

