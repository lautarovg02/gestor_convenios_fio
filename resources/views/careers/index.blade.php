@extends('layouts.app')

@section('content')
    <!-- Boton Agregar carrera -->
    <div class="d-flex justify-content-between align-items-center mt-1 mb-3">
        <a href="{{ route('careers.create') }}" class="btn btn-secondary" onclick="">
            Agregar Carrera <i class="bi bi-plus"></i>
        </a>

        <!-- Barra de búsqueda -->
        <form action="{{ route('careers.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Buscar carreras..." value="{{ request()->input('search') }}" style="min-width: 400px;">
            <button type="submit" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#modal-loading">Buscar</button>
        </form>
    </div>

    <!-- FILTROS-->
    <div class="col-12">
        @include('careers.filters')
    </form>

    <!--- Mensajes de error o success al editar, eliminar o crear entidad --->
    @if (Session::get('success'))
        <div class="alert alert-success">
            <p class="mb-1">{!! Session::get('success') !!}</p>
        </div>
    @elseif (Session::get('error'))
        <div class="alert alert-danger">
            <p class="mb-1">{!! Session::get('error') !!}</p>
        </div>
    @endif

    <!-- Mensaje de "Cargando" -->
    <div id="loading-message" style="display: none;">
        <div class="alert alert-info">
            Cargando, por favor espera...
        </div>
    </div>

    <!--- Mensaje en Busqueda: No se encuentran carreras --->
    @if (isset($noResults) && $noResults)
        <div class="alert alert-warning">
            No se encontraron resultados para la búsqueda: "{{ request()->input('search') }}"
        </div>
    @else
        @if (!$careers->isEmpty())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            <a
                                href="{{ route('careers.index', [
                                'search' => request()->input('search'),
                                'department' => request()->input('department'),
                                'sort' => 'name',
                                'direction' => request()->input('sort') === 'name' && request()->input('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                Carrera
                                @if (request()->input('sort') === 'name')
                                    @if (request()->input('direction') === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th>Departamento</th>
                        <th>Coordinador</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($careers as $career)
                        <tr>
                            <td>{{ $career->id }}</td>
                            <td>{!! highlightKeyword($career->name, request()->input('search')) !!}</td>
                            <td>{!! highlightKeyword($career->department->name ?? 'N/A', request()->input('search')) !!}</td>
                            <td>{!! highlightKeyword($career->teacher->name ?? 'N/A', request()->input('search')) !!}</td>
                            <td>
                                <a href="{{ route('careers.show', $career) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('careers.edit', $career) }}" class="btn btn-primary btn-sm">Editar</a>
                                <button type="button" class="btn btn-danger btn-sm" data-entity-id="{{$career->id}}" data-entity-name="{{$career->name}}" data-entity-type="careers" data-bs-toggle="modal" data-bs-target="#modal-delete">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $careers->appends(['search' => request()->input('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        @endif
    @endif

    <!-- Modal -->

    </div>

    <!--Linkeamos el .js del modal al template utilizando Vite-->


    @include('layouts.modals.modal-loading')
@endsection
