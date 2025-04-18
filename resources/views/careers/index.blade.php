@extends('layouts.app')

@section('title', 'Carreras FIO')

@section('content')
    <div class="container mt-4">

        <!-- Header con Título y Botón -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h4 class="fw-bold mb-1">Gestión de Carreras</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item text-muted">Gestión Académica</li>
                        <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Carreras</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('careers.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> Agregar Carrera
            </a>
        </div>

        <!-- Filtros -->
        <div class="mb-4">
            @include('careers.filters')
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

        @if (!$careers->isEmpty())
            <div class="card shadow-sm rounded">
                <div class="table-responsive table-scrollable-container">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th class="col-max-width">
                                    <a
                                        href="{{ route('careers.index', [
                                            'search' => request('search'),
                                            'department' => request('department'),
                                            'sort' => 'name',
                                            'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                        Carrera
                                        @if (request('sort') === 'name')
                                            @if (request('direction') === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="col-max-width">Departamento</th>
                                <th class="col-max-width">Coordinador</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($careers as $career)
                                <tr>
                                    <td>{{ $career->id }}</td>

                                    <td class="col-max-width text-truncate" title="{{ $career->name }}">
                                        {!! highlightKeyword($career->name, request('search')) !!}
                                    </td>

                                    <td class="col-max-width text-truncate"
                                        title="{{ $career->department->name ?? 'N/A' }}">
                                        {!! highlightKeyword($career->department->name ?? 'N/A', request('search')) !!}
                                    </td>

                                    <td class="col-max-width text-truncate"
                                        title="{{ $career->teacher->lastname . ' ' . $career->teacher->name }}">
                                        {!! highlightKeyword($career->teacher->lastname . ' ' . $career->teacher->name ?? 'N/A', request('search')) !!}
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('careers.show', $career) }}" class="btn btn-info btn-sm">Ver</a>
                                        <a href="{{ route('careers.edit', $career) }}"
                                            class="btn btn-primary btn-sm">Editar</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modal-delete" data-entity-id="{{ $career->id }}"
                                            data-entity-name="{{ $career->name }}" data-entity-type="careers">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer d-flex justify-content-center">
                    {{ $careers->appends(request()->all())->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif


        @include('layouts.modals.modal-delete')
        @include('layouts.modals.modal-loading')
        @vite('resources/js/modals/modalDelete.js')

    </div>
@endsection
