@extends('layouts.app')

@section('content')

<!-- Resto de tu vista para listar las empresas -->

<div class="container mt-1">
    <!-- Botón agregar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="#" class="btn btn-secondary" onclick="event.preventDefault();">
            Agregar Compañía <i class="bi bi-plus"></i>
        </a>

        <!-- Barra de búsqueda -->
        <form action="{{ route('companies.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Buscar empresas..." value="{{ request()->input('search') }}" style="min-width: 400px;">
            <button type="submit" class="btn btn-primary ms-2">Buscar</button>
        </form>
    </div>

    <!-- Mensajes de error, carga y busqueda sin resultados -->
    <div class="alert-container text-center mx-auto mb-3" style="max-width: 500px;">
        @if(isset($loadingMessage))
            <div class="alert alert-secondary p-2">
                {{ $loadingMessage }}
            </div>
        @endif
        @if(isset($errorMessage))
            <div class="alert alert-secondary error p-2">
                {{ $errorMessage }}
            </div>
        @elseif($companies->isEmpty() && request()->input('search'))
            <div class="alert alert-secondary p-2">
                No se encontraron resultados para "{{ request()->input('search') }}".<br>
                <a href="{{ route('companies.index') }}" class="btn btn-secondary mt-2">Realizar otra búsqueda</a>
            </div>
        @endif

        <!--- Mensajes de error o success al editar, eliminar o crear entidad --->
        @if (Session::get('success'))
            <div class="alert alert-success p-2">
                <p>{!! Session::get('success') !!}</p>
            </div>
        @elseif (Session::get('error'))
            <div class="alert alert-danger p-2">
                <p>{!! Session::get('error') !!}</p>
            </div>
        @endif
    </div>

    <!-- Tabla de resultados -->
    @if(!$companies->isEmpty())
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Denominación</th>
                <th>CUIT</th>
                <th>Nombre de la Compañía</th>
                <th>Sector</th>
                <th>Entidad</th>
                <th>Categoría</th>
                <th>Ciudad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->id }}</td>
                <td>{!! highlightKeyword($company->denomination, request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->cuit, request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->company_name ?? 'N/A', request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->sector ?? 'N/A', request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->entity ?? 'N/A', request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->company_category ?? 'N/A', request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->city->name ?? 'N/A', request()->input('search')) !!}</td>
                <td>
                    <a href="#" class="btn btn-info btn-sm">Ver</a>
                    <a href="#" class="btn btn-primary btn-sm">Editar</a>
                    <button type="button" class="btn btn-danger btn-sm" data-entity-id="{{$company->id}}" data-entity-name="{{$company->company_name}}" data-bs-toggle="modal" data-bs-target="#modal-delete">Eliminar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $companies->appends(['search' => request()->input('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
    @endif

    <!-- Modal -->
    @include('layouts/modals/modal-delete')
</div>

<!--Linkeamos el .js del modal al template utilizando Vite-->
@vite('resources/js/modals/modalDelete.js')

@endsection

