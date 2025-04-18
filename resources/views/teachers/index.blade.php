<!-- resources/views/teachers/index.blade.php -->
@extends('layouts.app')

@section('title', 'Docentes FIO')

@section('content')
    <div class="container mt-1">

        <!-- Botón agregar -->
        <div class="d-flex justify-content-between align-items-center mb-0git">
            <a href="{{ route('teachers.create') }}" class="btn btn-success" onclick="">
                Agregar un Docente <i class="bi bi-plus"></i>
            </a>

            <nav aria-label="breadcrumb" class="ms-3 mt-3">
                <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                    <li class="breadcrumb-item">
                        <span class="text-muted">Gestión Académica</span>
                    </li>
                    <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Docentes</li>

                </ol>
            </nav>

            <!-- Barra de búsqueda -->
            <form action="{{ route('teachers.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control font-size" placeholder="Buscar docentes..."
                    value="{{ request()->input('search') }}" style="min-width: 400px;">
                <button type="submit" class="btn btn-primary ms-2" data-bs-toggle="modal"
                    data-bs-target="#modal-loading">Buscar</button>
            </form>
        </div>

        <!-- FILTROS-->
        <div class="col-12">
            @include('teachers.filters')
        </div>

        <!-- Mensajes de error -->
        <div class="alert-container text-center mx-auto d-flex align-items-center justify-content-center">
            @if (isset($errorMessage))
                <div class="alert message-box alert-secondary error">
                    {{ $errorMessage }}
                </div>
            @elseif ($teachers->isEmpty())
                <div class="alert message-box alert-secondary error">
                    <p class="m-2">!La tabla de docentes, esta vacia!</p>
                </div>
            @endif

            <!--- Mensajes de error o success al editar, eliminar o crear entidad --->
            @if (Session::get('success'))
                <div class="alert message-box alert-success">
                    <p class="mb-1">{!! Session::get('success') !!}</p>
                </div>
            @elseif (Session::get('error'))
                <div class="alert message-box alert-danger">
                    <p class="mb-1">{!! Session::get('error') !!}</p>
                </div>
            @endif
        </div>

        <!-- Tabla de resultados -->
        @if (!$teachers->isEmpty())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Dni</th>
                        <th>Cuit</th>
                        <th>Rol</th>
                        <th>Es rector</th>
                        <th>Es decano</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->id }}</td>
                            <td class="text-truncate col-min-width col-max-width">{!! highlightKeyword($teacher->name, request()->input('search')) !!}
                                {!! highlightKeyword($teacher->lastname, request()->input('search')) !!}</td>
                            <td class="text-truncate col-min-width col-max-width">{!! highlightKeyword($teacher->dni, request()->input('search')) !!}</td>
                            <td class="text-truncate col-min-width col-max-width">{!! highlightKeyword($teacher->cuil ?? 'N/A', request()->input('search')) !!}</td>
                            <td class="text-truncate col-min-width col-max-width">
                                @if ($teacher->role == 'Director')
                                    <span class="badge bg-primary">Director</span>
                                @elseif ($teacher->role == 'Coordinador')
                                    <span class="badge bg-success">Coordinador</span>
                                @else
                                    <span class="badge bg-secondary">Sin Rol</span>
                                @endif
                            </td>
                            <td class="text-truncate col-min-width col-max-width">
                                <span class="badge {{ $teacher->is_rector ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $teacher->is_rector ? 'Rector' : 'No es Rector' }}
                                </span>
                            </td>

                            <td class="text-truncate col-min-width col-max-width">
                                <span class="badge {{ $teacher->is_dean ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $teacher->is_dean ? 'Decano' : 'No es Decano' }}
                                </span>
                            </td>
                            <td class="text-truncate col-min-width col-max-width">
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-primary btn-sm">Editar</a>
                                <button type="button" class="btn btn-danger btn-sm" data-entity-id="{{ $teacher->id }}"
                                    data-entity-name="{{ $teacher->name . ' ' . $teacher->lastname }}"
                                    data-entity-type="teachers" data-bs-toggle="modal"
                                    data-bs-target="#modal-delete">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $teachers->appends(array_merge(['search' => request()->input('search')], request()->only(['career', 'role'])))->onEachSide(1)->links('pagination::bootstrap-4') }}'
            </div>
        @endif
        @include('layouts/modals/modal-delete')

        <!-- Modal -->
        @vite('resources/js/modals/modalDelete.js')
        @include('layouts.modals.modal-loading')
    </div>
@endsection
