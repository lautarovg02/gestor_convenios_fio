@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('content')
<div class="container">
    <div class="card shadow-sm rounded">
        <div class="card-header">
            {{-- Muestra un título dinámico --}}
            <h4 class="mb-0">Datos del Usuario: {{ $user->teacher->name ?? $user->secretary->username ?? $user->name ?? 'Sin Nombre' }}</h4>
        </div>
        <div class="card-body">
            
            {{-- Advertencia (puedes quitarla si ya no es relevante) --}}
            @if(isset($warning))
                <div class="alert alert-warning">{{ $warning }}</div>
            @endif

            {{-- Sección de Datos de la Cuenta (Comunes a todos) --}}
            <h5 class="mb-3 border-bottom pb-2">Información de Cuenta</h5>
            <dl class="row">
                
                {{-- Nombre de Cuenta / Usuario --}}
                <dt class="col-sm-3">Usuario (Login):</dt>
                <dd class="col-sm-9">
                    {{ $user->name ?? $user->secretary->username ?? '—' }}
                </dd>

                {{-- Email --}}
                <dt class="col-sm-3">Email:</dt>
                <dd class="col-sm-9">{{ $user->email ?? '—' }}</dd>

                {{-- Rol de Sistema --}}
                <dt class="col-sm-3">Rol de Sistema:</dt>
                <dd class="col-sm-9 text-capitalize">{{ optional($user->role)->name ?? 'Sin rol asignado' }}</dd>
            </dl>

            <hr>

            {{-- Sección de Datos Adicionales (Docente / Secretaría) --}}
            <h5 class="mt-4 mb-3 border-bottom pb-2">
                @if ($user->teacher)
                    Datos del Docente (Teacher)
                @elseif ($user->secretary)
                    Datos de Secretaría
                @else
                    Datos Adicionales
                @endif
            </h5>

            <dl class="row">
                
                @if ($user->secretary)
                    {{-- Campo específico para Secretaría --}}
                    <dt class="col-sm-3">Nombre/Usuario de Secretaría:</dt>
                    <dd class="col-sm-9">{{ $user->secretary->username ?? '—' }}</dd>

                @elseif ($user->teacher)
                    {{-- Campos específicos para Docente (Teacher) --}}
                    
                    {{-- Nombre --}}
                    <dt class="col-sm-3">Nombre:</dt>
                    <dd class="col-sm-9">{{ $user->teacher->name ?? '—' }}</dd>

                    {{-- Apellido --}}
                    <dt class="col-sm-3">Apellido:</dt>
                    <dd class="col-sm-9">{{ $user->teacher->lastname ?? '—' }}</dd>

                    {{-- DNI --}}
                    <dt class="col-sm-3">DNI:</dt>
                    <dd class="col-sm-9">{{ $user->teacher->dni ?? '—' }}</dd>

                    {{-- CUIT (lo renombré de CUIL a CUIT por consistencia con tu index) --}}
                    <dt class="col-sm-3">CUIT:</dt>
                    <dd class="col-sm-9">{{ $user->teacher->cuil ?? '—' }}</dd>
                    
                    {{-- Facultad --}}
                    <dt class="col-sm-3">Facultad:</dt>
                    <dd class="col-sm-9">{{ $user->teacher->faculty ?? '—' }}</dd>

                @else
                    <dd class="col-sm-12">
                        <div class="alert alert-info mt-2 mb-0">
                            Este usuario no tiene datos de Docente o Secretaría asociados a su perfil.
                        </div>
                    </dd>
                @endif
            </dl>

        </div>

        <div class="card-footer text-end">
            <a href="{{ route('adminUsers.index') }}" class="btn btn-secondary">Volver al Listado</a>
            <a href="{{ route('adminUsers.edit', $user->id) }}" class="btn btn-primary">Editar Usuario <i class="bi bi-pencil-square"></i></a>
        </div>
    </div>
</div>
@endsection