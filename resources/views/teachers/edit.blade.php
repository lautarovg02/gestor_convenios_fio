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
                    <form method="POST" action="{{ route('teachers.update', $teacher) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Campo nombre -->
                        <div class="form-group mb-3">
                            <label class="form-label required-field" for="name">Nombre</label>
                            <div>
                                <input class="form-control" maxlength="40" name="name" id="name" type="text"
                                    value="{{ old('name', $teacher->name) }}" placeholder="Ingrese el nombre"
                                    autocomplete="off">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo apellido -->
                        <div class="form-group mb-3">
                            <label class="form-label required-field" for="lastname">Apellido</label>
                            <div>
                                <input class="form-control" maxlength="40" name="lastname" id="lastname" type="text"
                                    value="{{ old('lastname', $teacher->lastname) }}" placeholder="Ingrese el apellido"
                                    autocomplete="off">
                                @error('lastname')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo dni -->
                        <div class="form-group mb-3">
                            <label class="form-label required-field" for="dni">DNI</label>
                            <div>
                                <input class="form-control" name="dni" id="dni" type="number"
                                    value="{{ old('dni', $teacher->dni) }}" maxlength="8" placeholder="Ingrese el dni"
                                    autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                @error('dni')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo cuil -->
                        <div class="form-group mb-3">
                            <label class="form-label" for="cuil">CUIL</label>
                            <div>
                                <input class="form-control" name="cuil" id="cuil" type="number"
                                    value="{{ old('cuil', $teacher->cuil) }}" maxlength="11" placeholder="Ingrese el cuil"
                                    autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                @error('cuil')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Campo rector -->
                        <div class="form-group mb-3">
                            <label class="form-label" for="is_rector">Es rector</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div>
                                    <input class="form-check-input" type="radio" id="rector_1" name="is_rector" value="1"
                                        {{ old('is_rector', $teacher->is_rector) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rector_1">Es Rector</label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" id="rector_2" name="is_rector" value="0"
                                        {{ old('is_rector', $teacher->is_rector) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rector_2">No es Rector</label>
                                </div>
                            </div>
                            @error('is_rector')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Campo decano -->
                        <div class="form-group mb-3">
                            <label class="form-label" for="is_dean">Es decano</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div>
                                    <input class="form-check-input" type="radio" id="dean_1" name="is_dean" value="1"
                                        {{ old('is_dean', $teacher->is_dean) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dean_1">Es Decano</label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" id="dean_2" name="is_dean" value="0"
                                        {{ old('is_dean', $teacher->is_dean) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dean_2">No es Decano</label>
                                </div>
                            </div>
                            @error('is_dean')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>
                        <!-- Campo Rol -->
                        <div class="form-group mb-3">

                            <div class="alert alert-light   shadow-sm w-75 " role="alert">
                                <strong>Campo Rol:</strong> Los roles de <strong>Director de Departamento</strong> y <strong>Coordinador de Carrera</strong>
                                no se pueden modificar desde este formulario.
                                <br>
                                Estos roles se asignan automáticamente al editar un
                                <a href="{{ route('departments.index') }}" class="text-decoration-underline">departamento</a> (para director)
                                o una
                                <a href="{{ route('careers.index') }}" class="text-decoration-underline">carrera</a> (para coordinador).
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="text-end">
                                <div class="d-flex">
                                    <a href="{{ route('teachers.index') }}" class="btn btn-danger m-2">Cancelar</a>
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
