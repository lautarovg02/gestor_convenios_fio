@extends('layouts.app')

@section('title', 'Empresas FIO')

@section('content')
    <div class="container mt-4">

        <!-- Encabezado + Botón -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Gestión de Empresas</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Empresas</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('companies.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> Agregar Empresa
            </a>
        </div>

        <!-- Filtros -->
        <div class="mb-4">
            @include('companies.filters')
        </div>
        <!-- Mensajes -->
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @elseif (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif

        @if (isset($loadingMessage))
            <div class="alert alert-secondary text-center">{{ $loadingMessage }}</div>
        @elseif (isset($errorMessage))
            <div class="alert alert-danger text-center">{{ $errorMessage }}</div>
        @elseif ($companies->isEmpty() && request()->input('search'))
            <div class="alert alert-warning text-center">
                No se encontraron resultados para: <strong>"{{ request()->input('search') }}"</strong><br>
                <a href="{{ route('companies.index') }}" class="btn btn-secondary mt-2">Realizar otra búsqueda</a>
            </div>
        @endif

        <!-- Tabla de Empresas -->
        @unless ($companies->isEmpty())
        <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th class="col-max-width">Razón Social</th>
                        <th class="col-max-width">CUIT</th>
                        <th class="col-max-width">Nombre Fantasía</th>
                        <th class="col-max-width">Sector</th>
                        <th class="col-max-width">Entidad</th>
                        <th class="col-max-width">Categoría</th>
                        <th class="col-max-width">Ciudad</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                        <tr>
                            <td>{{ $company->id }}</td>

                            <td class="col-max-width text-truncate" title="{{ $company->denomination }}">
                                {{ highlightKeyword($company->denomination, request('search')) }}
                            </td>

                            <td class="col-max-width text-truncate" title="{{ $company->cuit }}">
                                {{ highlightKeyword($company->cuit, request('search')) }}
                            </td>

                            <td class="col-max-width text-truncate" title="{{ $company->company_name }}">
                                {{ highlightKeyword($company->company_name ?? 'N/A', request('search')) }}
                            </td>

                            <td class="col-max-width text-truncate" title="{{ $company->sector }}">
                                {{ highlightKeyword($company->sector ?? 'N/A', request('search')) }}
                            </td>

                            <td class="col-max-width text-truncate" title="{{ $company->entity->name }}">
                                {{ highlightKeyword($company->entity->name ?? 'N/A', request('search')) }}
                            </td>

                            <td class="col-max-width text-truncate" title="{{ $company->company_category }}">
                                {{ highlightKeyword($company->company_category ?? 'N/A', request('search')) }}
                            </td>

                            <td class="col-max-width text-truncate" title="{{ $company->city->name }}">
                                {{ highlightKeyword($company->city->name ?? 'N/A', request('search')) }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('companies.show', $company) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('companies.edit', $company) }}" class="btn btn-primary btn-sm">Editar</a>
                                <button type="button" class="btn btn-danger btn-sm"
                                    data-entity-id="{{ $company->id }}"
                                    data-entity-name="{{ $company->company_name }}"
                                    data-entity-type="companies"
                                    data-bs-toggle="modal" data-bs-target="#modal-delete">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


            <!-- Paginación -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $companies->appends(request()->except('page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        @endunless

        <!-- Modales -->
        @include('layouts.modals.modal-delete')
        @include('layouts.modals.modal-loading')

        @vite('resources/js/modals/modalDelete.js')
    </div>
@endsection
