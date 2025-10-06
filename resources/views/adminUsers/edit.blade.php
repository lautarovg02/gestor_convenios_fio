@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container">
    <div class="card shadow-sm rounded">
        <div class="card-header">
            <h4 class="mb-0">Editar Usuario</h4>
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

                {{-- Nombre/Usuario --}}
                <div class="mb-3">
                    <label class="form-label">Nombre / Usuario</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', optional($user->teacher)->name ?? optional($user->secretary)->user_name ?? $user->name) }}">
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <input type="text" class="form-control" value="{{ ucfirst($user->role->name ?? 'Sin rol') }}" disabled>
                </div>

                @if(optional($user->role)->name === 'secretary')
                <div class="mb-3">
                    <label class="form-label">Usuario (secretary)</label>
                    <input type="text" name="username" class="form-control"
                        value="{{ old('username', optional($user->secretary)->user_name ?? '') }}">
                </div>
                @endif

                <hr>

                {{-- Cambiar contraseña --}}
                <h6>Cambiar contraseña (opcional)</h6>
                <div class="mb-3">
                    <label class="form-label">Contraseña actual</label>
                    <input type="password" name="old_password" class="form-control">
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
                <a href="{{ route('adminUsers.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection