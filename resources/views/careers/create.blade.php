<!-- resources/views/companies/create.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')
    <div class="row">
        <div class="col-12">
            <div>
                <h4>Agregar Carrera nueva</h4>
            </div>

        </div>

    </div>

    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <form method="POST" action=" {{ route('careers.store') }} " id="" role="form">
                        @csrf
                        <div class="form-group mb-3 fs-6">
                            <label class="form-label required-field">
                                <label for="name" class="required-file">Carrera</label>
                            </label>
                            <div>
                                <input class="form-control " placeholder="Carrera a ingresar..." name="name"
                                    type="text" id="name">
                                <small class="form-hint">Carrera <b>nombre</b></small>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3 fs-6">
                            <label class="form-label required-field"> <label
                                    for="coordinator_id">Coordinador</label></label>
                            <div>
                                <select class=" form-control " name="coodinator_id" id="">
                                    <option value="">Seleccionar</option>
                                    @foreach ($coordinators as $coordinator)
                                        <option value="{{ $coordinator->id }}">{{ $coordinator->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-hint">Seleccione el <b>coordinador</b> al cual pertenece la
                                    carrera.</small>
                                @error('coordinator_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group mb-3 fs-6">
                            <label class="form-label required-field"> <label
                                    for="departament_id">Departamento</label></label>
                            <div>
                                <select class=" form-control " name="departament_id" id="">
                                    <option value="">Seleccionar</option>
                                    @foreach ($departaments as $departament)
                                        <option value="{{ $departament->id }}">{{ $departament->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-hint">Seleccione el <b>departamento</b> al cual pertenece la
                                    carrera.</small>
                                @error('departament_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="text-end">
                                <div class="d-flex">
                                    <a href="{{ route('careers.create') }}" class="btn btn-danger m-2">Cancelar</a>
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
