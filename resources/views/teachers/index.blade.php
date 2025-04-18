<!-- resources/views/teachers/index.blade.php -->
@extends('layouts.app')

@section('title', 'Docentes FIO')

@section('content')
    <div class="container mt-4">

        <!-- Header con Título y Botón -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Gestión de Docentes</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item text-muted">Gestión Académica</li>
                        <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Docentes</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('teachers.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> Agregar Docente
            </a>
        </div>

        <!-- Filtros -->
        <div class="mb-4">
            @include('teachers.filters')
        </div>

        <!-- Mensajes -->
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @elseif (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @elseif (isset($errorMessage))
            <div class="alert alert-warning">{{ $errorMessage }}</div>
        @elseif ($teachers->isEmpty())
            <div class="alert alert-info">¡La tabla de docentes está vacía!</div>
        @endif

        @if (!$teachers->isEmpty())
            <div class="card shadow-sm rounded">
                <div class="table-responsive rounded shadow-sm table-scrollable-container">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th class="col-max-width">Nombre</th>
                                <th>DNI</th>
                                <th>CUIT</th>
                                <th>Rol</th>
                                <th>Rector</th>
                                <th>Decano</th>
                                <th class="text-center" style="width: 200px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->id }}</td>

                                    <td class="text-truncate col-max-width"
                                        title="{{ $teacher->name . ' ' . $teacher->lastname }}">
                                        {!! highlightKeyword($teacher->name, request('search')) !!}
                                        {!! highlightKeyword($teacher->lastname, request('search')) !!}
                                    </td>

                                    <td>{{ $teacher->dni }}</td>
                                    <td>{{ $teacher->cuil ?? 'N/A' }}</td>

                                    <td>
                                        <span
                                            class="badge {{ $teacher->role == 'Director' ? 'bg-primary' : ($teacher->role == 'Coordinador' ? 'bg-success' : 'bg-secondary') }}">
                                            {{ $teacher->role ?? 'Sin Rol' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge {{ $teacher->is_rector ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $teacher->is_rector ? 'Rector' : 'No es Rector' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge {{ $teacher->is_dean ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $teacher->is_dean ? 'Decano' : 'No es Decano' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('teachers.show', $teacher) }}"
                                            class="btn btn-info btn-sm">Ver</a>
                                        <a href="{{ route('teachers.edit', $teacher) }}"
                                            class="btn btn-primary btn-sm">Editar</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modal-delete" data-entity-id="{{ $teacher->id }}"
                                            data-entity-name="{{ $teacher->name . ' ' . $teacher->lastname }}"
                                            data-entity-type="teachers">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer d-flex justify-content-center">
                    {{ $teachers->appends(request()->all())->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif


        @include('layouts.modals.modal-delete')
        @include('layouts.modals.modal-loading')
        @vite('resources/js/modals/modalDelete.js')
    </div>
@endsection
