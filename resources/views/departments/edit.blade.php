<!-- resources/views/departments/edit.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')

<div class="row row-deck row-cards justify-content-center">
    <div class="col-8 ">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                <h3 class="card-title"> Detalles del departamento</h3>
                <a href="{{route('departments.index')}}" class="btn btn-secondary m-2">Volver</a>
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
                    action="{{ route('departments.update', $department) }}" id="edit-department-form" role="form" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    @csrf

                    <!-- Campo Departamento -->
                    <div class="form-group mb-3">
                        <label class="form-label required-field" for= "cuit">Denominación del departamento</label>
                        <div>
                            <input class="form-control" name="name" id="name" type="text" value="{{old('name', $department->name)}}"
                                placeholder="Departamento " autocomplete="off">
                            <small class="form-hint">Modifique el <b>nombre</b> del departamento de ser necesario.</small>
                        </div>
                        @error('name')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <!-- Campo Director -->
                    <!-- Selector con docentes sin rol" -->
                    <div class="form-group mb-3">
                        <label class="form-label fs-6 required-field" for="director_id">Director de departamento</label>
                        <select name="director_id" id="director_id" class="form-select">
                            <option value="" disabled>Seleccione un director</option>

                            <!-- Primero mostrar el director actual si no hay errores de validación -->
                            <option value="{{ $department->director_id }}"
                                {{ old('director_id', $department->director_id) == $department->director_id ? 'selected' : '' }}>
                                {{ $department->teacher->lastname }} {{ $department->teacher->name }}
                            </option>

                            <!-- Luego mostrar los docentes disponibles que no tienen roles -->
                            @foreach ($teachersWithoutRol as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('director_id', $department->director_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->lastname }} {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('director_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-footer">
                        <div class="text-end">
                            <div class="d-flex">
                                <a href="{{route('departments.index')}}" class="btn btn-danger m-2">Cancelar</a>
                                <button type="button" class="btn btn-success ms-auto m-2"
                                    data-id= "{{$department->id}}"
                                    data-department-name="{{old('name' , $department->name) }}"
                                    data-director-id="{{$department->director_id}}"
                                    data-director-name="{{$department->teacher->name}}"
                                    data-director-lastname="{{$department->teacher->lastname}}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit">Guardar modificación</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @include('layouts/modals/modal-edit')
</div>

<!--Link a .js del modal al template utilizando Vite-->
@vite('resources/js/modals/modalEdit.js')

@endsection
