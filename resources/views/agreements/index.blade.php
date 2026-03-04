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

        @if (isset($noResults) && $noResults && (request('search') || request('status') || request('type')))
            <div class="alert alert-warning">
                No se encontraron resultados con los filtros aplicados.
            </div>
        @endif

        @if (!$agreements->isEmpty())
            <div class="card shadow-sm rounded">
                <div class="table-responsive rounded shadow-sm table-scrollable-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="col-max-width">Fecha</th>
                                <th class="col-max-width">Estado</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Subtipo</th>
                                <th class="text-center">Razón Social</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agreements as $agreement)
                                {{-- Fila del Convenio Marco --}}
                                <tr>
                                    <td class="text-center">{{ $agreement->creation_date }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ optional($agreement->status)->status ?? 'Sin Estado' }}</span>
                                    </td>
                                    <td class="text-center fw-bold">{{ optional($agreement->typeFrameworkAgreement)->type }}</td>
                                    <td class="text-center text-muted">Marco</td>
                                    <td class="text-center">{{ optional($agreement->company)->company_name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('agreements.show', $agreement->id) }}" class="btn btn-primary btn-sm" title="Ver Convenio Marco">
                                            Ver <i class="bi bi-file-earmark-text"></i>
                                        </a>
                                        <button type="button" class="btn btn-success btn-sm" title="Descargar Convenio Marco">
                                            Descargar <i class="bi bi-download me-1"></i>
                                        </button>
                                    </td>
                                </tr>

                                @php $statusFilter = request('status', ''); @endphp

                                {{-- Hijos: Convenio Específico --}}
                                @if($agreement->type_framework_agreement_id == 3 && $agreement->specifics->isNotEmpty())
                                    @foreach($agreement->specifics as $specific)
                                        @if(!$statusFilter || optional($specific->status)->status === $statusFilter)
                                        <tr class="table-light">
                                            <td class="text-center"><i class="bi bi-arrow-return-right text-muted me-2"></i>{{ $specific->signing_date ?? 'Sin Fecha' }}</td>
                                            <td class="text-center"><span class="badge bg-info text-dark">{{ optional($specific->status)->status ?? 'Sin Estado' }}</span></td>
                                            <td class="text-center text-muted">Convenio Específico</td>
                                            <td class="text-center text-muted">Particular</td>
                                            <td class="text-center text-muted">{{ optional($agreement->company)->company_name ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('specificAgreement.show', $specific->id) }}" class="btn btn-outline-primary btn-sm">Ver <i class="bi bi-file-earmark-text"></i></a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif

                                {{-- Hijos: Acuerdo Individual de Pasantía --}}
                                @if($agreement->type_framework_agreement_id == 1 && $agreement->individualIntershipAgreements->isNotEmpty())
                                    @foreach($agreement->individualIntershipAgreements as $individual)
                                        @if(!$statusFilter || optional($individual->status)->status === $statusFilter)
                                        <tr class="table-light">
                                            <td class="text-center"><i class="bi bi-arrow-return-right text-muted me-2"></i>{{ $individual->signing_date ?? 'Sin Fecha' }}</td>
                                            <td class="text-center"><span class="badge bg-info text-dark">{{ optional($individual->status)->status ?? 'Sin Estado' }}</span></td>
                                            <td class="text-center text-muted">Acu. Indiv. de Pasantía</td>
                                            <td class="text-center text-muted">Particular</td>
                                            <td class="text-center text-muted">{{ optional($agreement->company)->company_name ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('individual-internship-agreements.show', $individual->id) }}" class="btn btn-outline-primary btn-sm">Ver <i class="bi bi-file-earmark-text"></i></a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif

                                {{-- Hijos: Acuerdo Específico de Residencia --}}
                                @if($agreement->type_framework_agreement_id == 2 && $agreement->specificResidenceAgreements->isNotEmpty())
                                    @foreach($agreement->specificResidenceAgreements as $residence)
                                        @if(!$statusFilter || optional($residence->status)->status === $statusFilter)
                                        <tr class="table-light">
                                            <td class="text-center"><i class="bi bi-arrow-return-right text-muted me-2"></i>{{ $residence->signing_date ?? 'Sin Fecha' }}</td>
                                            <td class="text-center"><span class="badge bg-info text-dark">{{ optional($residence->status)->status ?? 'Sin Estado' }}</span></td>
                                            <td class="text-center text-muted">Acu. Espec. de Residencia</td>
                                            <td class="text-center text-muted">Particular</td>
                                            <td class="text-center text-muted">{{ optional($agreement->company)->company_name ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('specificResidenceAgreement.show', $residence->id) }}" class="btn btn-outline-primary btn-sm">Ver <i class="bi bi-file-earmark-text"></i></a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
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