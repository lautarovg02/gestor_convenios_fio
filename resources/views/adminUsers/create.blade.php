@extends('layouts.app')

@section('title', 'Agregar Usuario')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h4 class="fw-bold mb-1">Agregar nuevo usuario</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item text-muted">Gestión de Usuarios</li>                  
                    <li class="breadcrumb-item"><a href="{{ route('adminUsers.index') }}" class="text-muted">Usuarios</a></li> 
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Crear</li>
                </ol>
            </nav>
        </div>
       
        <a href="{{ route('adminUsers.index') }}" class="btn btn-outline-primary">
            ← Volver
        </a>
    </div>

    <div class="card shadow-sm rounded">
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-bold">Detalles de Usuario</h4>
        </div>

        <div class="card-body">
            {{-- Formulario --}}
            <form action="{{ route('adminUsers.store') }}" method="POST"> 
                @csrf

                {{-- Errores globales --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="row g-3">
                    {{-- Columna 1: Datos de Acceso y Rol --}}
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-none">
                            <div class="card-header bg-light fw-bold border rounded-top">                            
                                Datos de Acceso
                            </div>
                            <div class="card-body border border-top-0 rounded-bottom">
                                
                                {{-- Nombre de usuario --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label required-field">Nombre de Usuario</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Ej: jdoe">
                                    <small class="text-muted">Se usará para iniciar sesión.</small>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label required-field">Correo Electrónico</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Contraseña --}}
                                <div class="mb-3">
                                    <label for="password" class="form-label required-field">Contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Rol --}}
                                <div class="mb-3">
                                    <label for="role_id" class="form-label required-field">Rol del Sistema</label>
                                    <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un rol</option>
                                        @if(isset($roles))
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                            
                                            {{-- Identificamos los IDs para JavaScript --}}
                                            @php
                                                $teacherRole = $roles->firstWhere('name', 'Docente');
                                                $teacherRoleId = $teacherRole ? $teacherRole->id : 0;

                                                $coordinatorRole = $roles->firstWhere('name', 'Coordinador');
                                                $coordinatorRoleId = $coordinatorRole ? $coordinatorRole->id : 0;

                                                $directorRole = $roles->firstWhere('name', 'Director');
                                                $directorRoleId = $directorRole ? $directorRole->id : 0;
                                                
                                                $secretaryRole = $roles->firstWhere('name', 'Secretaria');
                                                $secretaryRoleId = $secretaryRole ? $secretaryRole->id : 0;
                                            @endphp
                                        @endif
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Columna 2: Datos de Perfil (Dinámico) --}}
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-none">
                            <div class="card-header bg-light fw-bold border rounded-top">    
                                Datos del Perfil  
                            </div>
                            
                            <div class="card-body border border-top-0 rounded-bottom">
                                
                                {{-- BLOQUE MENSAJE POR DEFECTO --}}
                                <div id="no_profile_msg" class="alert alert-secondary text-center mt-4">
                                    <i class="bi bi-info-circle"></i><br>
                                    Seleccione el rol <strong>Docente, Coordinador o Director</strong> para cargar datos académicos.<br>
                                    Los demás roles no requieren información adicional.
                                </div>

                                {{-- BLOQUE CAMPOS DOCENTE --}}
                                <div id="teacher_fields" style="display: none;">
                                    <div class="alert alert-info py-2 small">
                                        <i class="bi bi-exclamation-circle me-1"></i> Complete los datos académicos.
                                    </div>

                                    <div class="row">
                                        {{-- Nombre Docente --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="docente_nombre" class="form-label required-field">Nombre</label>
                                            <input type="text" name="docente_nombre" id="docente_nombre" class="form-control @error('docente_nombre') is-invalid @enderror" value="{{ old('docente_nombre') }}">
                                            @error('docente_nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        {{-- Apellido Docente --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="docente_apellido" class="form-label required-field">Apellido</label>
                                            <input type="text" name="docente_apellido" id="docente_apellido" class="form-control @error('docente_apellido') is-invalid @enderror" value="{{ old('docente_apellido') }}">
                                            @error('docente_apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        {{-- DNI --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="dni" class="form-label required-field">DNI</label>
                                            <input type="text" name="dni" id="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni') }}">
                                            @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        {{-- CUIL --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="cuil" class="form-label">CUIL</label>
                                            <input type="text" name="cuil" id="cuil" class="form-control @error('cuil') is-invalid @enderror" value="{{ old('cuil') }}">
                                            @error('cuil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Facultad --}}
                                    <div class="mb-3">
                                        <label for="facultad" class="form-label">Facultad</label>
                                        <input type="text" name="facultad" id="facultad" class="form-control @error('facultad') is-invalid @enderror" value="{{ old('facultad') }}">
                                    </div>

                                    <hr>
                                    
                                    {{-- Autoridades --}}
                                    <label class="form-label fw-bold mb-2">Cargos de Autoridad</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_rector" id="is_rector" value="1" {{ old('is_rector') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_rector">Es Rector</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_dean" id="is_dean" value="1" {{ old('is_dean') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_dean">Es Decano</label>
                                        </div>
                                    </div>

                                </div> 
                                {{-- Fin teacher_fields --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end border-top pt-3">
                    <a href="{{ route('adminUsers.index') }}" class="btn btn-secondary me-2">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Crear Usuario
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Obtenemos los IDs de los roles pasados desde PHP/Blade
        const TEACHER_ROLE_ID = {{ $teacherRoleId ?? 0 }};
        const COORDINATOR_ROLE_ID = {{ $coordinatorRoleId ?? 0 }};
        const DIRECTOR_ROLE_ID = {{ $directorRoleId ?? 0 }};
        
        const roleSelect = document.getElementById('role_id');
        const teacherFields = document.getElementById('teacher_fields');
        const noProfileMsg = document.getElementById('no_profile_msg');

        function toggleFields() {
            const selectedRole = roleSelect.value;
            const isTeacher = selectedRole == TEACHER_ROLE_ID || selectedRole == COORDINATOR_ROLE_ID || selectedRole == DIRECTOR_ROLE_ID;

            if (isTeacher) {
                // Mostrar campos docentes
                teacherFields.style.display = 'block';
                noProfileMsg.style.display = 'none';
                
                // Habilitar inputs
                teacherFields.querySelectorAll('input').forEach(el => el.disabled = false);
            } else {
                // Ocultar campos docentes
                teacherFields.style.display = 'none';
                noProfileMsg.style.display = 'block';

                // Deshabilitar inputs para que no interfieran en la validación
                teacherFields.querySelectorAll('input').forEach(el => el.disabled = true);
            }
        }

        // Ejecutar al inicio y al cambiar
        toggleFields();
        roleSelect.addEventListener('change', toggleFields);
    });
</script>