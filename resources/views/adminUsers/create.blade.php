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
                    <li class="breadcrumb-item"><a class="text-muted">Usuarios</a></li> 
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Crear</li>
                </ol>
            </nav>
        </div>
       
          {{-- Enlace "Volver" usando la ruta index --}}
        <a href="{{ route('adminUsers.index') }}" class="btn btn-outline-primary">
            ← Volver
        </a>
    </div>

    <div class="card shadow-sm rounded">
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-bold">Detalles de Usuario</h4>
        </div>

        <div class="card-body">
            {{-- Formulario de Creación de Usuario  --}}
           
            <form action="{{ route('admin.users.store') }}" method="POST"> 
                @csrf
                {{-- Mensajes de error globales (si los hay) --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Usamos g-3 para el espaciado consistente entre columnas --}}
                <div class="row g-3">
                    {{-- Columna 1: Datos de Acceso y Rol --}}
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header  bg-light fw-bold">                            
                                Datos de Acceso
                            </div>
                            <div class="card-body">
                                
                                {{-- Nombre de usuario --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre de Usuario</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Contraseña --}}
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Rol --}}
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Rol del Sistema</label>
                                    <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un rol</option>
                                        {{-- Asegúrate de que $roles se pasa desde el controlador --}}
                                       @if(isset($roles))
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
            </option>
        @endforeach
        {{-- 💡 Identificamos los IDs para JavaScript --}}
        @php
            $teacherRole = $roles->firstWhere('name', 'teacher');
            $teacherRoleId = $teacherRole ? $teacherRole->id : 0;
            
            $secretaryRole = $roles->firstWhere('name', 'secretary');
            $secretaryRoleId = $secretaryRole ? $secretaryRole->id : 0; // <-- ¡Añadir esta línea!
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

                    {{-- Columna 2: Datos de usuario --}}
                    <div class="col-md-6">
                        <div class="card h-100 ">
                            <div id="teacher_fields"> 
                              <div class="card-header  bg-light fw-bold">     
                                Datos del Usuario  

                            </div>
                                <div  class="px-3 pt-2" >   
                            <small class="form-hint">Completar campos obligatorios  <b>*</b> para un docente.</small>
                                </div>
                            <div class="card-body">
                                
                                {{-- Nombre Docente --}}
                                <div class="mb-3">
                                    <label for="docente_nombre" class="form-label required-field fs-8">Nombre</label>
                                    <input type="text" name="docente_nombre" id="docente_nombre" class="form-control @error('docente_nombre') is-invalid @enderror" value="{{ old('docente_nombre') }}">
                                    @error('docente_nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Apellido Docente --}}
                                <div class="mb-3">
                                    <label for="docente_apellido" class="form-label required-field fs-8">Apellido</label>
                                    <input type="text" name="docente_apellido" id="docente_apellido" class="form-control @error('docente_apellido') is-invalid @enderror" value="{{ old('docente_apellido') }}">
                                    @error('docente_apellido')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- DNI --}}
                                <div class="mb-3">
                                    <label for="dni" class="form-label required-field fs-8">DNI</label>
                                    <input type="text" name="dni" id="dni" class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni') }}">
                                    @error('dni')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- CUIL --}}
                                <div class="mb-3">
                                    <label for="cuil" class="form-label">CUIL</label>
                                    <input type="text" name="cuil" id="cuil" class="form-control @error('cuil') is-invalid @enderror" value="{{ old('cuil') }}">
                                    @error('cuil')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Facultad --}}
                                <div class="mb-3">
                                    <label for="facultad" class="form-label">Facultad</label>
                                    <input type="text" name="facultad" id="facultad" class="form-control @error('facultad') is-invalid @enderror" value="{{ old('facultad') }}">
                                    @error('facultad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
  </div>
                    </div>


                </div>

                <div class="mt-4 d-flex justify-content-end">
                   
                    {{-- Botón "Cancelar" usando la ruta index --}}
                    <a href="{{ route('adminUsers.index') }}" class="btn btn-danger m-2">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-success m-2">
                        Crear Usuario
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
        const SECRETARY_ROLE_ID = {{ $secretaryRoleId ?? 0 }}; // <-- Usamos este ID
        
        const roleSelect = document.getElementById('role_id');
        const teacherFields = document.getElementById('teacher_fields');

        // Función para cambiar la visibilidad y el estado 'disabled'
        function toggleTeacherFields() {
            // El contenedor se debe mostrar/habilitar SOLO si el rol seleccionado es TEACHER.
            const isTeacher = roleSelect.value == TEACHER_ROLE_ID;

            if (isTeacher) {
                // ROL TEACHER: MOSTRAR y HABILITAR CAMPOS
                teacherFields.style.display = ''; 
                teacherFields.querySelectorAll('input, select').forEach(field => {
                    field.disabled = false;
                });
                
            } else if (roleSelect.value == SECRETARY_ROLE_ID) {
                // ROL SECRETARY: OCULTAR y DESHABILITAR CAMPOS
                teacherFields.style.display = 'none';
                teacherFields.querySelectorAll('input, select').forEach(field => {
                    field.disabled = true; // Deshabilita para que no se envíen
                });
                
            } else {
                // Para cualquier otro rol (ej: Alumno, Admin), por defecto se oculta
                teacherFields.style.display = 'none';
                teacherFields.querySelectorAll('input, select').forEach(field => {
                    field.disabled = true;
                });
            }
        }

        // 1. Ejecutar al cargar la página (para manejar 'old' values)
        toggleTeacherFields();

        // 2. Ejecutar cada vez que el valor del selector de rol cambie
        roleSelect.addEventListener('change', toggleTeacherFields);
    });
</script>