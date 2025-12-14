@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('content')
<div class="container">
    <div class="card shadow-sm rounded">
        <div class="card-header bg-primary text-white">
            {{-- Muestra un título dinámico CORREGIDO --}}
            <h4 class="mb-0">
                Datos del Usuario: 
                @if($user->teacher)
                    {{ $user->teacher->name }} {{ $user->teacher->lastname }}
                @else
                    {{ $user->name }}
                @endif
            </h4>
        </div>
        <div class="card-body">
            
            @if(isset($warning))
                <div class="alert alert-warning">{{ $warning }}</div>
            @endif

            {{-- Sección de Datos de la Cuenta (Comunes a todos) --}}
            <h5 class="mb-3 border-bottom pb-2 text-primary">Información de Cuenta</h5>
            <dl class="row">
                
                {{-- Nombre de Cuenta / Usuario --}}
                <dt class="col-sm-3">Nombre de Usuario:</dt>
                <dd class="col-sm-9">
                    {{ $user->name }}
                </dd>

                {{-- Email --}}
                <dt class="col-sm-3">Email:</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>

                {{-- Rol de Sistema (SPATIE) --}}
                <dt class="col-sm-3">Rol de Sistema:</dt>
                <dd class="col-sm-9">
                    <span class="badge bg-secondary text-uppercase">
                        {{ $user->getRoleNames()->first() ?? 'Sin rol asignado' }}
                    </span>
                </dd>
            </dl>

            <hr>

            {{-- Sección de Datos Adicionales --}}
            <h5 class="mt-4 mb-3 border-bottom pb-2 text-primary">
                @if ($user->teacher)
                    Datos del Perfil Docente
                @elseif ($user->hasRole('secretary'))
                    Datos del Perfil Secretaría
                @else
                    Datos Adicionales
                @endif
            </h5>

            <dl class="row">
                
                @if ($user->teacher)
                    {{-- Campos específicos para Docente (Teacher) --}}
                    
                    <dt class="col-sm-3">Nombre Completo:</dt>
                    <dd class="col-sm-9">{{ $user->teacher->name }} {{ $user->teacher->lastname }}</dd>

                    <dt class="col-sm-3">DNI:</dt>
                    <dd class="col-sm-9">{{ $user->teacher->dni ?? '—' }}</dd>

                    <dt class="col-sm-3">CUIL/CUIT:</dt>
                    <dd class="col-sm-9">{{ $user->teacher->cuil ?? '—' }}</dd>
                    
                    <dt class="col-sm-3">Facultad:</dt>
                    <dd class="col-sm-9">{{ $user->teacher->faculty ?? '—' }}</dd>
                    
                    <dt class="col-sm-3">Autoridad:</dt>
                    <dd class="col-sm-9">
                        @if($user->teacher->is_rector) <span class="badge bg-info text-dark">Rector</span> @endif
                        @if($user->teacher->is_dean) <span class="badge bg-info text-dark">Decano</span> @endif
                        @if(!$user->teacher->is_rector && !$user->teacher->is_dean) No aplica @endif
                    </dd>

                @elseif ($user->hasRole('secretary'))
                    {{-- 
                        Como borramos la columna 'username' de la tabla 'secretaries',
                        ya no hay datos extra que mostrar aquí. El perfil de secretaria
                        es básicamente un usuario con permisos especiales.
                    --}}
                    <dd class="col-sm-12">
                        <div class="alert alert-light border">
                            <i class="bi bi-info-circle me-2"></i>
                            El perfil de Secretaría utiliza los datos principales de la cuenta de usuario.
                        </div>
                    </dd>

                @else
                    <dd class="col-sm-12">
                        <div class="text-muted">
                            Este usuario no tiene un perfil extendido (Docente o Secretaría).
                        </div>
                    </dd>
                @endif
            </dl>

        </div>

        <div class="card-footer text-end bg-light">
            <a href="{{ route('adminUsers.index') }}" class="btn btn-outline-secondary">Volver al Listado</a>
            <a href="{{ route('adminUsers.edit', $user->id) }}" class="btn btn-primary">
                Editar Usuario <i class="bi bi-pencil-square ms-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection