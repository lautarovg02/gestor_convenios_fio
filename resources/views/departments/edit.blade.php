<!-- resources/views/departments/edit.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')

<div class="row row-deck row-cards">
    <div class="col-12 ">
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
                    action="{{ route('departments.update', $department) }}" id="" role="form" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    @csrf

                    {{-- edit form --}}

                    <!-- Campo nombre departamento -->
                    <div class="form-group mb-3">
                        <label class="form-label required-field fs-6" for='name'> Denominación</label>
                        <div>
                            <input class="form-control" maxlength="200" name="name" id="name" type="text" value="{{$department->name}}" placeholder="Ingrese la denominación " autocomplete="off">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Campo Director -->
                    <div class="mb-3">
                        <label class="form-label required-field" for="director">Director de Departamento</label>
                        <!-- Campos Apellido y Nombre en una fila -->
                        <div class="row">
                            <!-- Campo Apellido -->
                            <div class="col-md-6 mb-3">
                                <input class="form-control" name="director" id="director" type="text" value="{{$department->teacher->lastname}}" placeholder="Ingrese el apellido del director" autocomplete="off">
                                @error('director')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>

                            <!-- Campo Nombre -->
                            <div class="col-md-6 mb-3">
                                <input class="form-control" name="director_id" id="director_id" type="text" value="{{$department->teacher->name}}" placeholder="Ingrese el nombre del director" autocomplete="off">
                                @error('director_id')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="form-footer">
                        <div class="text-end">
                            <div class="d-flex">
                                <a href="{{route('departments.index')}}" class="btn btn-danger m-2">Cancelar</a>
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
