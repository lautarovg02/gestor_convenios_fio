@extends('layouts.app')

@section('content')
    <div class="row row-deck row-cards content-with-footer-buffer">
        <div class="col-12 ">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                    <h3 class="card-title">Editar Alumno</h3>
                    <nav aria-label="breadcrumb" class="ms-3 mt-3">
                        <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                            <li class="breadcrumb-item">
                                <span class="text-muted">Gestión Académica</span>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('students.index') }}">Alumnos</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="text-muted">
                                    {{ $student->name }} {{ $student->last_name }}
                                </span>
                            </li>
                            <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-primary">← Volver</a>
                </div>

                <div>
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @if ($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- O PATCH, dependiendo de tu controlador --}}

                        <div class="row">
                            {{-- Columna Izquierda: Datos Personales --}}
                            <div class="col-md-6">
                                <h5 class="mb-3 text-primary border-bottom pb-2">Datos Personales</h5>

                                <div class="form-group mb-3">
                                    <label class="form-label required-field" for="name">Nombre</label>
                                    <input class="form-control @error('name') is-invalid @enderror" maxlength="255" name="name" id="name" type="text"
                                        value="{{ old('name', $student->name) }}" placeholder="Ingrese el nombre" autocomplete="off">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label required-field" for="last_name">Apellido</label>
                                    <input class="form-control @error('last_name') is-invalid @enderror" maxlength="255" name="last_name" id="last_name" type="text"
                                        value="{{ old('last_name', $student->last_name) }}" placeholder="Ingrese el apellido" autocomplete="off">
                                    @error('last_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label required-field" for="dni">DNI</label>
                                    <input class="form-control @error('dni') is-invalid @enderror" name="dni" id="dni" type="number"
                                        value="{{ old('dni', $student->dni) }}" maxlength="8" placeholder="Ingrese el DNI"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    @error('dni')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label" for="cuil">CUIL</label>
                                    <input class="form-control @error('cuil') is-invalid @enderror" name="cuil" id="cuil" type="number"
                                        value="{{ old('cuil', $student->cuil) }}" maxlength="11" placeholder="Ingrese el CUIL"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    @error('cuil')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Columna Derecha: Contacto y Dirección --}}
                            <div class="col-md-6">
                                <h5 class="mb-3 text-primary border-bottom pb-2">Contacto y Ubicación</h5>

                                <div class="form-group mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input class="form-control @error('email') is-invalid @enderror" name="email" id="email" type="email"
                                        value="{{ old('email', $student->email) }}" placeholder="ejemplo@email.com">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label" for="phone_numb">Teléfono</label>
                                    <input class="form-control @error('phone_numb') is-invalid @enderror" name="phone_numb" id="phone_numb" type="text"
                                        value="{{ old('phone_numb', $student->phone_numb) }}" placeholder="Ingrese teléfono">
                                    @error('phone_numb')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-8">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="street">Calle</label>
                                            <input class="form-control @error('street') is-invalid @enderror" name="street" id="street" type="text"
                                                value="{{ old('street', $student->street) }}" placeholder="Calle">
                                            @error('street')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="number">Número</label>
                                            <input class="form-control @error('number') is-invalid @enderror" name="number" id="number" type="text"
                                                value="{{ old('number', $student->number) }}" placeholder="Nro">
                                            @error('number')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label" for="city">Ciudad</label>
                                    <input class="form-control @error('city') is-invalid @enderror" name="city" id="city" type="text"
                                        value="{{ old('city', $student->city) }}" placeholder="Ingrese la ciudad">
                                    @error('city')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label" for="career">Carrera</label>
                                    <input class="form-control @error('career') is-invalid @enderror" name="career" id="career" type="text"
                                        value="{{ old('career', $student->career) }}" placeholder="Carrera">
                                    @error('career')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-footer mt-4">
                            <div class="text-end">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('students.index') }}" class="btn btn-danger m-2">Cancelar</a>
                                    <button type="submit" class="btn btn-success m-2">Guardar cambios</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection