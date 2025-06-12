@extends('layouts.app')

@section('title', 'Carreras FIO')

@section('content')
<div class="container content-with-footer-buffer">
        <!-- Header con Título y Botón -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h4 class="fw-bold mb-1">Convenios</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item text-muted">Convenios</li>
                        <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Todos los convenios</li>
                    </ol>
                </nav>
            </div>
            {{-- <a href="{{ route('careers.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> Agregar Carrera
            </a> --}}
        </div>

        <!-- Filtros -->
        
        <div class="mb-4">
            @include('agreements.filters')
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

        @if (!$agreements->isEmpty())
            <div class="card shadow-sm rounded">
                <div class="table-responsive rounded shadow-sm table-scrollable-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="col-max-width">Fecha de inicio</th>
                                <th class="col-max-width">Estado</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Razón Social</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agreements as $agreement)
                                <tr>
                                    <td class="text-center">{{ $agreement->creation_date }}</td>
                                    <td class="text-center">{{ $agreement->status->status }}</td>
                                    <td class="text-center">{{ $agreement->typeFrameworkAgreement->type }}</td>
                                    <td class="text-center">{{ $agreement->company->company_name}}</td>
                                    <td class="text-center">
                                        <a href="{{ route('agreements.show', $agreement) }}" class="btn btn-primary btn-sm">
                                            Ver <i class="bi bi-file-earmark-text"></i>
                                        </a>

                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" >
                                            Descargar  <i class="bi bi-download me-1"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer d-flex justify-content-center">
                    {{ $agreements->appends(request()->all())->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif


        @include('layouts.modals.modal-delete')
        @include('layouts.modals.modal-loading')
        @vite('resources/js/modals/modalDelete.js')

    </div>
@endsection