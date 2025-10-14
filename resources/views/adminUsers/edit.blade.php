@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container">
    <div class="card shadow-sm rounded">
        <div class="card-header">
            <h4 class="mb-0">Editar Usuario: {{ $user->teacher->name ?? $user->secretary->username ?? $user->name ?? 'Sin Nombre' }}</h4>
        </div>

        <form action="{{ route('adminUsers.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
                {{-- Mostrar errores --}}
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                {{-- Rol del Usuario --}}
                @php $roleName = optional($user->role)->name; @endphp

                <h5 class="mb-3 border-bottom pb-2">Información de Cuenta</h5>

                {{-- Campo Rol (Solo Lectura) --}}
                <div class="mb-3">
                    <label class="form-label">Rol de Sistema</label>
                    <input type="text" class="form-control" value="{{ ucfirst($roleName ?? 'Sin rol') }}" disabled>
                </div>

                {{-- Nombre de Cuenta / Usuario (Campo 'name' del modelo User) --}}
                <div class="mb-3">
                    <label class="form-label">Usuario (Login)</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $user->name) }}" required>
                    <small class="form-text text-muted">Este es el nombre usado para iniciar sesión (si aplica). No es el nombre del Docente/Secretaría.</small>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" 
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <hr>
                <h5 class="mt-4 mb-3 border-bottom pb-2">
                    @if ($roleName === 'teacher')
                        Datos del Docente (Teacher)
                    @elseif ($roleName === 'secretary')
                        Datos de Secretaría
                    @else
                        Datos Adicionales
                    @endif
                </h5>

                {{-- Campos Específicos para Docente (Teacher) --}}
                @if($user->teacher)
                    {{-- Nombre (teacher->name) --}}
                    <div class="mb-3">
                        <label class="form-label">Nombre del Docente</label>
                        <input type="text" name="teacher_name" class="form-control"
                            value="{{ old('teacher_name', optional($user->teacher)->name) }}" required>
                    </div>

                    {{-- Apellido (teacher->lastname) --}}
                    <div class="mb-3">
                        <label class="form-label">Apellido del Docente</label>
                        <input type="text" name="teacher_lastname" class="form-control"
                            value="{{ old('teacher_lastname', optional($user->teacher)->lastname) }}" required>
                    </div>

                    {{-- DNI (teacher->dni) --}}
                    <div class="mb-3">
                        <label class="form-label">DNI</label>
                        <input type="text" name="teacher_dni" class="form-control"
                            value="{{ old('teacher_dni', optional($user->teacher)->dni) }}">
                    </div>

                    {{-- CUIT (teacher->cuil) --}}
                    <div class="mb-3">
                        <label class="form-label">CUIT</label>
                        <input type="text" name="teacher_cuil" class="form-control"
                            value="{{ old('teacher_cuil', optional($user->teacher)->cuil) }}">
                    </div>
                    
                    {{-- Facultad (teacher->faculty) --}}
                    <div class="mb-3">
                        <label class="form-label">Facultad</label>
                        <input type="text" name="teacher_faculty" class="form-control"
                            value="{{ old('teacher_faculty', optional($user->teacher)->faculty) }}">
                    </div>

                {{-- Campos Específicos para Secretaría --}}
                @elseif($user->secretary)
                    <div class="mb-3">
                        <label class="form-label">Nombre/Usuario de Secretaría</label>
                        <input type="text" name="secretary_username" class="form-control"
                            value="{{ old('secretary_username', optional($user->secretary)->username) }}" required>
                    </div>

                @else
                    <div class="alert alert-info">
                        Este usuario no tiene datos de Docente o Secretaría que puedan ser editados.
                    </div>
                @endif

                <hr>
                <h5 class="mt-4 mb-3 border-bottom pb-2">Cambiar Contraseña (Opcional)</h5>
                <p class="text-muted small">Solo completa estos campos si deseas modificar la contraseña del usuario.</p>
                
                {{-- Contraseña actual (para validar si es el propio usuario el que cambia, o para seguridad si lo hace un admin) --}}
                <div class="mb-3">
                    <label class="form-label">Contraseña actual (solo si la cambia el propio usuario)</label>
                    <input type="password" name="old_password" class="form-control">
                    <small class="form-text text-muted">Dejar vacío si no se desea cambiar la contraseña.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nueva contraseña</label>
                    <input type="password" name="new_password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar nueva contraseña</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>

            </div>

            <div class="card-footer text-end">
                <a href="{{ route('adminUsers.show', $user->id) }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection