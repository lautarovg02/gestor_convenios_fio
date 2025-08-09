@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Header con Título y Breadcrumb (MISMO DISEÑO QUE DOCENTES) -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h4 class="fw-bold mb-1">Agregar nuevo alumno</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item text-muted">Gestión Académica</li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Alumnos</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
            ← Volver
        </a>
    </div>

    <!-- Card con formulario -->
    <div class="card shadow-sm rounded">
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-bold">Detalles Alumno</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Ej: Juan" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Apellido -->
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Apellido</label>
                        <input type="text" name="last_name" id="last_name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            value="{{ old('last_name') }}" placeholder="Ej: Rodríguez" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- DNI solo números -->
                    <div class="col-md-4">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" 
                               name="dni" 
                               id="dni"
                               class="form-control @error('dni') is-invalid @enderror"
                               value="{{ old('dni') }}" 
                               placeholder="Ej: 40123456" 
                               required
                               maxlength="8"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        @error('dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CUIL solo números -->
                    <div class="col-md-4">
                        <label class="form-label" for="cuil">CUIL</label>
                        <input class="form-control @error('cuil') is-invalid @enderror" 
                               name="cuil" id="cuil" type="text"
                               value="{{ old('cuil') }}" maxlength="11" 
                               placeholder="Ingrese el CUIL"
                               autocomplete="off" 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        @error('cuil')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Teléfono solo numérico -->
                    <div class="col-md-4">
                        <label class="form-label" for="phone_numb">Teléfono</label>
                        <input class="form-control @error('phone_numb') is-invalid @enderror" 
                               name="phone_numb" id="phone_numb" type="text"
                               value="{{ old('phone_numb') }}" 
                               maxlength="12"
                               placeholder="Ej: 2284123456"
                               autocomplete="off"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        @error('phone_numb')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Ej: alumno@unicen.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Calle (Domicilio) -->
                    <div class="col-md-6">
                        <label for="street" class="form-label">Calle (Domicilio)</label>
                        <input type="text" name="street" id="street"
                               class="form-control @error('street') is-invalid @enderror"
                               value="{{ old('street') }}" placeholder="Ej: San Martín" required>
                        @error('street')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Número -->
                    <div class="col-md-3">
                        <label for="number" class="form-label">Número</label>
                        <input type="text" name="number" id="number"
                               class="form-control @error('number') is-invalid @enderror"
                               value="{{ old('number') }}" placeholder="Ej: 123"
                               maxlength="5" required
                               oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ciudad -->
                    <div class="col-md-3">
                        <label for="city" class="form-label">Ciudad</label>
                        <input type="text" name="city" id="city"
                               class="form-control @error('city') is-invalid @enderror"
                               value="{{ old('city') }}" placeholder="Ej: Olavarría" required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Carrera -->
                    <div class="col-md-6">
                        <label for="career" class="form-label">Carrera</label>
                        <select name="career" id="career"
                                class="form-select @error('career') is-invalid @enderror" required>
                            <option value="">Seleccione una carrera...</option>
                            @foreach($careers as $career)
                                <option value="{{ $career->name }}" {{ old('career') == $career->name ? 'selected' : '' }}>
                                    {{ $career->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('career')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('students.index') }}" class="btn btn-danger m-2">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-success m-2">
                        Guardar Alumno
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
