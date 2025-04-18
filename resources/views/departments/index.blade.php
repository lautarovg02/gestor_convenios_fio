@extends('layouts.app')

@section('title', 'Departamentos FIO')

@section('content')
<div class="container mt-4">

    <!-- Header con Título y Botón -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h4 class="fw-bold mb-1">Gestión de Departamentos</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item text-muted">Gestión Académica</li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Departamentos</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('departments.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Agregar Departamento
        </a>
    </div>

    <!-- Mensajes -->
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @elseif (Session::get('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    @if ($noResults)
        <div class="alert alert-warning text-center">No se encontraron departamentos.</div>
    @endif

    <!-- Tabla -->
    @unless ($noResults)
    <div class="card shadow-sm rounded">
        <div class="table-responsive rounded shadow-sm table-scrollable-container">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th class="col-max-width">Denominación</th>
                        <th class="col-max-width">Director del Departamento</th>
                        <th class="text-center" style="width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                        <tr>
                            <td>{{ $department->id }}</td>

                            <td class="text-truncate col-max-width" title="{{ $department->name }}">
                                {!! highlightKeyword($department->name, request('search')) !!}
                            </td>

                            <td class="text-truncate col-max-width" title="{{ $department->teacher->lastname . ' ' . $department->teacher->name }}">
                                {!! highlightKeyword($department->teacher->lastname . ' ' . $department->teacher->name, request('search')) !!}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('departments.show', $department) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('departments.edit', $department) }}" class="btn btn-primary btn-sm">Editar</a>
                                <button type="button" class="btn btn-danger btn-sm"
                                    data-entity-id="{{ $department->id }}"
                                    data-entity-name="{{ $department->name }}"
                                    data-entity-type="departments"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-delete">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-center">
            {{ $departments->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endunless


    <!-- Modal -->
    @include('layouts.modals.modal-delete')
    @include('layouts.modals.modal-loading')
    @vite('resources/js/modals/modalDelete.js')

</div>
@endsection
