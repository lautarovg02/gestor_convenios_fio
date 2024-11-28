<!-- resources/views/careers/edit.blade.php -->
@extends('layouts.app')

@section('content')

<div class="row row-deck row-cards justify-content-center">
    <div class="col-8 ">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                <h3 class="card-title"> Detalles de la carrera</h3>
                <a href="{{route('careers.index')}}" class="btn btn-secondary m-2">Volver</a>
            </div>
            <div>
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
            </div>

            <div class="card-body">
                <form method="POST"
                    action="{{ route('careers.update', $career) }}" id="edit-career-form" role="form" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    @csrf

                    <!-- Campo nombre Carrera -->
                    <div class="form-group mb-3">
                        <label class="form-label required-field" for= "name">Carrera</label>
                        <div>
                            <input class="form-control" name="name" id="name" type="text" value="{{old('name', $career->name)}}"
                                placeholder="Carrera" autocomplete="off">
                            <small class="form-hint">Modifique el <b>nombre</b> de la carrera de ser necesario.</small>
                        </div>
                        @error('name')
                            <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <!-- Campo nombre Departamento al cual pertenece -->
                    <!-- Selector con departamentos" -->
                    <div class="form-group mb-3">
                        <label class="form-label fs-6 required-field" for="department_id">Departamento</label>
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="" disabled>Seleccione un departamento</option>

                            <!-- Primero mostrar el departamento actual si no hay errores de validación -->
                            <option value="{{ $career->department_id }}"
                                {{ old('department_id', $career->department_id) == $career->department_id ? 'selected' : '' }}>
                                {{ $career->department->name }}
                            </option>

                            <!-- Luego mostrar los departamentos sin seleccionar-->
                            @foreach ($departments as $department)
                                @if ($department->id !== $career->department_id)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $career->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <div>
                            <small class="form-hint">Modifique el <b>departamento</b> al cual pertenece la carrera  de ser necesario.</small>
                        </div>
                        @error('department_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campo coordinador -->
                    <!-- Selector con docentes sin rol" -->
                    <div class="form-group mb-3">
                        <label class="form-label fs-6 required-field" for="coordinator_id">Coordinador de carrera</label>
                        <select name="coordinator_id" id="coordinator_id" class="form-select">
                            <option value="" disabled>Seleccione un coordinador</option>

                            <!-- Primero mostrar el coordinador actual si no hay errores de validación -->
                            <option value="{{ $career->coordinator_id }}"
                                {{ old('coordinator_id', $career->coordinator_id) == $career->coordinator_id ? 'selected' : '' }}>
                                {{ $career->teacher->lastname }} {{ $career->teacher->name }}
                            </option>

                            <!-- Luego mostrar los docentes disponibles para coordinador porque no tienen roles asignados -->
                            @foreach ($teachersWithoutRol as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('coordinator_id', $career->coordinator_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->lastname }} {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('coordinator_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-footer">
                        <div class="text-end">
                            <div class="d-flex">
                                <a href="{{route('careers.index')}}" class="btn btn-danger m-2">Cancelar</a>
                                <button type="button" class="btn btn-success ms-auto m-2"
                                    data-id= "{{$career->id}}"
                                    data-career-name="{{old('name' , $career->name) }}"
                                    data-department-name="{{$career->department->name}}"
                                    data-coordinator-id="{{$career->coordinator_id}}"
                                    data-coordinator-name="{{$career->teacher->name}}"
                                    data-coordinator-lastname="{{$career->teacher->lastname}}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit-career">Guardar modificación</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @include('layouts/modals/modal-edit-career')
</div>

<!--Link a .js del modal al template utilizando Vite-->
@vite('resources/js/modals/modalEditCareer.js')

@endsection
