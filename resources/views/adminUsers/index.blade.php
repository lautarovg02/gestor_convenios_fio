@extends('layouts.app')

@section('title', 'Usuarios FIO')

@section('content')
<div class="container content-with-footer-buffer">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h4 class="fw-bold mb-1">Usuarios</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item active text-dark" aria-current="page">Todos los usuarios</li>
                </ol>
            </nav>
        </div>

        {{-- Botón para agregar usuario --}}
        <a href="{{ route('adminUsers.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Agregar Usuario
        </a>
    </div>

    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @elseif (Session::get('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    @if (isset($noResults) && $noResults)
        <div class="alert alert-warning">
            No se encontraron resultados para: <strong>"{{ request('search') }}"</strong>
        </div>
    @endif

    @if (!$users->isEmpty())
    <div class="card shadow-sm rounded">
        <div class="table-responsive rounded shadow-sm table-scrollable-container">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Usuario</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Rol de Sistema</th> 
                        {{-- Se eliminaron: Nombre, Apellido, DNI, CUIT, Facultad --}}
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            {{-- Nombre / Usuario --}}
                            <td class="text-center">
                                {{ $u->name ?? $u->secretary->username ?? '—' }}
                            </td>

                            {{-- Email --}}
                            <td class="text-center">{{ $u->email }}</td> 

                            {{-- Rol de sistema --}}
                            <td class="text-center">{{ $u->role->name ?? '—' }}</td> 
                            {{-- Se eliminaron las <td> de Nombre, Apellido, DNI, CUIT y Facultad --}}
                            
                            {{-- Acciones --}}
                            <td class="text-center">
                                <a href="{{ route('adminUsers.show', $u->id) }}" class="btn btn-info btn-sm">
                                    Ver datos <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('adminUsers.edit', $u->id) }}" class="btn btn-primary btn-sm">
                                    Editar <i class="bi bi-file-earmark-text"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm"
                                    data-entity-name="{{ $u->teacher->name ?? $u->secretary->username ?? '—' }}"
                                    data-action="{{ route('adminUsers.destroy', $u->id) }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-delete">
                                    Eliminar <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Modales --}}
    @include('layouts.modals.modal-delete')
    @include('layouts.modals.modal-loading')

    @vite('resources/js/modals/modalDelete.js')
</div>
@endsection