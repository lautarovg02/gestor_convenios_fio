<!-- resources/views/companies/create.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')


<div class="row row-deck row-cards">
    <div class="col-12 ">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                <h3 class="card-title">Agregar un nuevo departamento</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item"><span class="text-muted">Gestión Académica</span></li>
                        <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departamentos</a></li>
                        <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Crear Departamento</li>
                    </ol>
                </nav>
                <a href="{{route('departments.index')}}" class="btn btn-outline-primary">← Volver</a>
            </div>

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



            <div class="card-body">
                <form method="POST" action=" {{route('departments.store')}} " id="" role="form">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label">
                            <label for="name" class="required-field fs-6">Nombre</label>
                        </label>
                        <div>
                            <input class="form-control fs-6" maxlength="200" placeholder="Nombre" name="name" type="text" id="name">
                        </div>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fs-6 required-field">
                            <label for="director">Director</label>
                        </label>
                        <div>
                            <select class="form-control" name="director_id" id="">
                                    <option value="" disabled selected hidden>Seleccionar Director</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{$teacher->id}}">{{$teacher->lastname}} {{$teacher->name}}</option>
                                    @endforeach
                            </select>
                            <small class="form-hint">Si no encuentra al <b>docente</b> en la lista, agregarlo en  <a href="{{route('teachers.create')}}">Agregar Docente</a>.</small>
                            @error('director_id')
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-footer">
                        <div class="text-end">
                            <div class="d-flex">
                                <a href="{{route('departments.index')}}" class="btn btn-danger m-2">Cancelar</a>
                                <div>
                                    <button type="submit" class="btn btn-success ms-auto m-2">Crear</button>
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
