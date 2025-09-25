@extends('layouts.app')

@section('title', 'Usuarios FIO')

@section('content')
<div class="container content-with-footer-buffer">
    <!-- Header con Título -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h4 class="fw-bold mb-1">Usuarios</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">

                    <li class="breadcrumb-item active text-dark " aria-current="page">Todos los usuarios</li>
                </ol>
            </nav>
        </div>

        {{-- Botón para agregar usuario --}}
        <a href="{{ route('adminUsers.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Agregar Usuario
        </a>
    </div>

    <!-- Mensajes -->
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

    @if (!$users->isEmpty() )
    <div class="card shadow-sm rounded">
        <div class="table-responsive rounded shadow-sm table-scrollable-container">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="col-max-width text-center">Usuario</th>
                        <th class="col-max-width text-center">Email</th>
                        <th class="text-center">Tipo de rol</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Secretarios --}}
                    @foreach ($users as $u)
                    <tr>
                        <td class="text-center">{{ $u->teacher->name ?? $u->secretary->username ?? '—'  }}</td>
                        <td class="text-center">{{ $u['email'] }}</td>
                        <td class="text-center">{{ $u->role->name }}</td>
                        <td class="text-center">
                            <a href="{{ route('adminUsers.show', $u['id']) }}" class="btn btn-info btn-sm">
                                Ver datos <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('adminUsers.edit', $u['id']) }}" class="btn btn-primary btn-sm">
                                Editar datos <i class="bi bi-file-earmark-text"></i>
                            </a>
                            <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-delete"
                                data-action="{{ route('adminUsers.destroy', $u->id) }}"
                                data-entity="{{ $u->teacher->name ?? $u->secretary->username ?? $u->email }}">
                                Eliminar <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach


                    @endif

                    {{-- Modales --}}
                    @include('layouts.modals.modal-delete')
                    @include('layouts.modals.modal-loading')

                    @vite('resources/js/modals/modalDelete.js')
        </div>
        @endsection