@extends('layouts.app')

@section('content')

<div class="row row-deck row-cards justify-content-center content-with-footer-buffer">
    <div class="col-12 ">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                <h3 class="card-title"> Detalles de la carrera</h3>
                <nav aria-label="breadcrumb" class="ms-3 mt-3">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item"><span class="text-muted">Gestión Académica</span></li>
                        <li class="breadcrumb-item"><a href="{{ route('careers.index') }}">Carreras</a></li>
                        <li class="breadcrumb-item">
                            <span class="text-muted">
                                {{ $career->name }}
                            </span>
                        </li>
                        <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Editar</li>
                    </ol>
                </nav>
                <a href="{{route('careers.index')}}" class="btn btn-outline-primary">← Volver</a>
            </div>
            
            <div>
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{Session::get('success')}}
                    </div>
                @endif

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

                    <div class="form-group mb-3">
                        <label class="form-label fs-6 required-field" for="department_id">Departamento</label>
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="" disabled>Seleccione un departamento</option>

                            <option value="{{ $career->department_id }}"
                                {{ old('department_id', $career->department_id) == $career->department_id ? 'selected' : '' }}>
                                {{ optional($career->department)->name ?? 'Sin departamento asignado' }}
                            </option>

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

                    <div class="form-group mb-3">
                        <label class="form-label fs-6 required-field" for="coordinator_id">Coordinador de carrera</label>
                        <select name="coordinator_id" id="coordinator_id" class="form-select">
                            <option value="" disabled>Seleccione un coordinador</option>

                            <option value="{{ $career->coordinator_id }}"
                                {{ old('coordinator_id', $career->coordinator_id) == $career->coordinator_id ? 'selected' : '' }}>
                                @if($career->teacher)
                                    {{ $career->teacher->lastname }} {{ $career->teacher->name }}
                                @else
                                    Sin coordinador asignado
                                @endif
                            </option>

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
                                
                                {{-- 
                                    IMPORTANTE: 
                                    He protegido los atributos data-* con ?? '' para que no exploten si son null.
                                    Si usas un modal JS que lee esto, asegúrate que maneje strings vacíos.
                                --}}
                                <button type="button" class="btn btn-success ms-auto m-2"
                                    data-id= "{{$career->id}}"
                                    data-career-name ="{{old('name' , $career->name) }}"
                                    data-department-id="{{$career->department_id}}"
                                    data-department-name="{{ optional($career->department)->name ?? ''}}"
                                    data-coordinator-id="{{$career->coordinator_id}}"
                                    data-coordinator-name="{{ $career->teacher->name ?? ''}}"
                                    data-coordinator-lastname="{{ $career->teacher->lastname ?? ''}}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-edit-career">Guardar modificación</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts/modals/modal-edit-career')
</div>

@vite('resources/js/modals/modalEditCareer.js')

@endsection