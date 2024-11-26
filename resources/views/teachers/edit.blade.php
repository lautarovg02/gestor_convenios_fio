<!-- resources/views/companies/edit.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')
    <div class="row row-deck row-cards">
        <div class="col-12 ">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                    <h3 class="card-title"> Detalles del docente</h3>

                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary m-2">Volver</a>
                </div>
                <div>
                    <!-- Mensajes flash de success-->
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
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
                    <form method="POST" action="{{ route('teachers.update', $teacher) }}" id="" role="form"
                        enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        @csrf

                        <!-- Campo nombre -->
                        <div class="form-group mb-3">
                            <label class="form-label required" for= "teacher_name">Nombre</label>
                            <div>
                                <input class="form-control" name="teacher_name" id="teacher_name" type="text"
                                    value="{{ $teacher->name }}" placeholder="Ingrese el nombre" autocomplete="off">
                            </div>
                        </div>

                        <!-- Campo apellido -->
                        <div class="form-group mb-3">
                            <label class="form-label required" for= "teacher_lastname">Apellido</label>
                            <div>
                                <input class="form-control" name="teacher_lastname" id="teacher_lastname" type="text"
                                    value="{{ $teacher->lastname }}" placeholder="Ingrese el apellido" autocomplete="off">
                            </div>
                        </div>

                        <!-- Campo dni -->
                        <div class="form-group mb-3">
                            <label class="form-label required" for= "teacher_dni">DNI</label>
                            <div>
                                <input class="form-control" name="teacher_dni" id="teacher_dni" type="number"
                                    value="{{ $teacher->dni }}" maxlength="8" placeholder="Ingrese el dni"
                                    autocomplete="off">
                            </div>
                        </div>

                        <!-- Campo cuil -->
                        <div class="form-group mb-3">
                            <label class="form-label " for= "teacher_cuil">CUIL</label>
                            <div>
                                <input class="form-control" name="teacher_cuil" id="teacher_cuil" type="number"
                                    maxlength="11" value="{{ $teacher->CUIL }}" placeholder="Ingrese el cuil"
                                    autocomplete="off">
                            </div>
                        </div>

                        <!-- Campo ROL -->
                        <div class="form-group mb-3">
                            <label class="form-label " for= "teacher_cuil">Rol</label>
                            <div>
                                <div class="alert alert-info" role="alert">
                                    Recurde, si quiere cambiar el rol de un docente, debera hacerlo desde el propio Departamento o la propia Carrera
                                </div>
                            </div>
                        </div>


                        <!-- Campo Rector -->
                        <div class="form-group mb-3">
                            <label class="form-label required" for= "teacher_rector">Es rector</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div>
                                    <input class="form-check-input" type="radio" id="rector_1" name="teacher_rector"
                                        value="true"
                                        {{ old('is_rector', $teacher->is_rector) == true ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rector_1">
                                        Es Rector
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" id="rector_2" name="scope"
                                        value="false"
                                        {{ old('is_rector', $teacher->is_rector) == false ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rector_2">
                                        No Es Rector
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Campo Decano -->
                        <div class="form-group mb-3">
                            <label class="form-label required" for= "teacher_dean">Es Decano</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div>
                                    <input class="form-check-input" type="radio" id="dean_1" name="teacher_dean"
                                        value="true"
                                        {{ old('is_dean', $teacher->is_dean) == true ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dean_1">
                                        Es Decano
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" id="dean_2" name="teacher_dean"
                                        value= "false"
                                        {{ old('is_dean', $teacher->is_dean) == false ? 'checked' : 'false' }}>
                                    <label class="form-check-label" for="dean_2">
                                        No Es Decano
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="text-end">
                                <div class="d-flex">
                                    <a href="{{ route('companies.index') }}" class="btn btn-danger m-2">Cancelar</a>
                                    <button type="submit" class="btn btn-success ms-auto m-2">Guardar
                                        modificación
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
