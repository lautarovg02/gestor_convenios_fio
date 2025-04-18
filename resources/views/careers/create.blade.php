<!-- resources/views/careers/create.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')
    <div class="row">
        <div class="row">
            <div class="col-12 d-flex justify-content-between align-items-center ps-4 pe-4">
                    <h4>Crear nueva carrera</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                            <li class="breadcrumb-item"><span class="text-muted">Gestión Académica</span></li>
                            <li class="breadcrumb-item"><a href="{{ route('careers.index') }}">Carreras</a></li>
                            <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Crear Carrera</li>
                        </ol>
                    </nav>
                    <a href="{{route('careers.index')}}" class="btn btn-outline-primary">← Volver</a>
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
                                <input class="form-control" maxlength="255" placeholder="Carrera a ingresar..." name="name" type="text" id="name">
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
                                <select class="form-control" name="coordinator_id" id="">
                                    <option value="">Seleccionar</option>
                                    @foreach ($coordinators as $coordinator)
                                        <option value="{{ $coordinator->id }}">{{ $coordinator->name . " " . $coordinator->lastname }}</option>
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
                                    for="department_id">Departamento</label></label>
                            <div>
                                <select class=" form-control " name="department_id" id="">
                                    <option value="">Seleccionar</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-hint">Seleccione el <b>departamento</b> al cual pertenece la
                                    carrera.</small>
                                @error('department_id')
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
