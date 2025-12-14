@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container">
    <div class="card shadow-sm rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                Editar Usuario: 
                @if($user->teacher)
                    {{ $user->teacher->name }} {{ $user->teacher->lastname }}
                @else
                    {{ $user->name }}
                @endif
            </h4>
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
                
                {{-- Obtener Rol con Spatie --}}
                @php 
                    $roleName = $user->getRoleNames()->first(); 
                @endphp

                <h5 class="mb-3 border-bottom pb-2 text-primary">Información de Cuenta</h5>

                {{-- Campo Rol (Solo Lectura) --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Rol de Sistema</label>
                    <input type="text" class="form-control bg-light" value="{{ ucfirst($roleName ?? 'Sin rol') }}" disabled>
                    <small class="text-muted">El rol no se puede cambiar desde esta pantalla.</small>
                </div>

                {{-- Nombre de Cuenta / Usuario --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre de Usuario (Login)</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $user->name) }}" required>
                    <small class="form-text text-muted">Nombre principal de la cuenta.</small>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" 
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <hr>
                
                {{-- Título Dinámico --}}
                <h5 class="mt-4 mb-3 border-bottom pb-2 text-primary">
                    @if ($roleName === 'teacher')
                        Datos del Perfil Docente
                    @elseif ($roleName === 'secretary')
                        Datos del Perfil Secretaría
                    @else
                        Datos Adicionales
                    @endif
                </h5>

                {{-- Campos Específicos para Docente (Teacher) --}}
                @if($user->teacher)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="teacher_name" class="form-control"
                                value="{{ old('teacher_name', $user->teacher->name) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="teacher_lastname" class="form-control"
                                value="{{ old('teacher_lastname', $user->teacher->lastname) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">DNI</label>
                            <input type="text" name="teacher_dni" class="form-control"
                                value="{{ old('teacher_dni', $user->teacher->dni) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">CUIL / CUIT</label>
                            <input type="text" name="teacher_cuil" class="form-control"
                                value="{{ old('teacher_cuil', $user->teacher->cuil) }}">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Facultad</label>
                            <input type="text" name="teacher_faculty" class="form-control"
                                value="{{ old('teacher_faculty', $user->teacher->faculty) }}">
                        </div>
                    </div>

                {{-- Campos Específicos para Secretaría --}}
                @elseif($user->hasRole('secretary'))
                    <div class="alert alert-light border">
                        <i class="bi bi-info-circle me-2"></i>
                        El perfil de Secretaría utiliza los datos principales de la cuenta (Usuario y Email). No hay datos adicionales para editar.
                    </div>
                    
                    {{-- 
                       IMPORTANTE: Eliminamos el input 'secretary_username' 
                       porque esa columna ya no existe en la base de datos.
                    --}}

                @else
                    <div class="alert alert-secondary">
                        Este usuario no tiene un perfil extendido editable.
                    </div>
                @endif

                <hr>
                
                {{-- Cambio de Contraseña --}}
                <h5 class="mt-4 mb-3 border-bottom pb-2 text-primary">Cambiar Contraseña (Opcional)</h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Dejar vacío para mantener la actual">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                    </div>
                </div>

            </div>

            <div class="card-footer text-end bg-light">
                <a href="{{ route('adminUsers.show', $user->id) }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection