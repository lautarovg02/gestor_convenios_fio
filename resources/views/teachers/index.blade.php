<!-- resources/views/teachers/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mt-1">

        <!-- Botón agregar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('teachers.create') }}" class="btn btn-success" onclick="">
                Agregar un Docente <i class="bi bi-plus"></i>
            </a>

            <!-- Barra de búsqueda -->
            <form action="{{ route('teachers.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Buscar docentes..."
                    value="{{ request()->input('search') }}" style="min-width: 400px;">
                <button type="submit" class="btn btn-primary ms-2">Buscar</button>
            </form>
        </div>


        <!-- Mensajes de error -->
        <div class="alert-container text-center mx-auto d-flex align-items-center justify-content-center">
            @if (isset($errorMessage))
                <div class="alert alert-secondary error">
                    {{ $errorMessage }}
                </div>
            @elseif ($teachers->isEmpty())
                <div class="alert alert-secondary error">
                   <p class="m-2">No se han encontrado Docentes</p>
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
                            <td >{{ $teacher->id }}</td>
                            <td class="text-truncate col-min-width col-max-width">{!! highlightKeyword($teacher->name, request()->input('search')) !!} {!! highlightKeyword($teacher->lastname, request()->input('search')) !!}</td>
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
                                <span class="badge {{ $teacher->is_rector ? 'bg-success' : 'bg-danger' }}">
                                    {{ $teacher->is_rector ? 'Rector' : 'No es Rector' }}
                                </span>
                            </td>

                            <td class="text-truncate col-min-width col-max-width">
                                <span class="badge {{ $teacher->is_dean ? 'bg-success' : 'bg-danger' }}">
                                    {{ $teacher->is_dean ? 'Decano' : 'No es Decano' }}
                                </span>
                            </td>
                            <td class="text-truncate col-min-width col-max-width">
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-primary btn-sm">Editar</a>
                                <button type="button" class="btn btn-danger btn-sm" data-entity-id="{{ $teacher->id }}"
                                    data-entity-name="{{ $teacher->name . ' ' . $teacher->lastname }}"
                                    data-bs-toggle="modal" data-bs-target="#modal-delete">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $teachers->appends(['search' => request()->input('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>

        @endif

    </div>
@endsection
