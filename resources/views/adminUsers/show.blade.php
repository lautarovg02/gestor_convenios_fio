@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('content')
<div class="container">
    <div class="card shadow-sm rounded">
        <div class="card-header">
            <h4 class="mb-0">Datos del Usuario</h4>
        </div>
        <div class="card-body">
            @if(isset($warning))
                <div class="alert alert-warning">{{ $warning }}</div>
            @endif

            @php $role = optional($user->role)->name; @endphp

            @if($role === 'secretary')
                <p><strong>Usuario:</strong> {{ $user->secretary->username ?? '—' }}</p>
                <p><strong>Email:</strong> {{ $user->email ?? '—' }}</p>
                <p><strong>Rol:</strong> {{ $role }}</p>

            @elseif($role === 'teacher')
                {{-- En principio los teachers se redirigen a /teachers/{id}.
                     Si llegás acá como fallback, mostramos algunos datos --}}
                <p><strong>Nombre:</strong> {{ $user->teacher->name ?? '—' }}</p>
                <p><strong>Apellido:</strong> {{ $user->teacher->lastname ?? '—' }}</p>
                <p><strong>Email:</strong> {{ $user->email ?? '—' }}</p>
                <p><strong>Rol:</strong> {{ $role }}</p>

            @else
                {{-- Otros roles --}}
                <p><strong>Nombre / Usuario:</strong> {{ $user->teacher->name ?? $user->secretary->username ?? '—' }}</p>
                <p><strong>Email:</strong> {{ $user->email ?? '—' }}</p>
                <p><strong>Rol:</strong> {{ $role ?? '—' }}</p>
            @endif
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('adminUsers.index') }}" class="btn btn-secondary">Volver</a>

            {{-- Si existe relation teacher y querés permitir editar --}}
            <a href="{{ route('adminUsers.edit', $user->id) }}" class="btn btn-primary">Editar</a>
        </div>
    </div>
</div>
@endsection
