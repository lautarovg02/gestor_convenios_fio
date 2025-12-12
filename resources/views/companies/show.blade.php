@extends('layouts.app')

@section('content')
    <div class=" d-flex flex-column container-xl mt-4 mb-5 justify-content-center align-items-center">
        <div class=" w-75 row justify-content-between align-items-center mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Empresa</a></li>
                        <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">
                            {{ $company->company_name }}</li>
                    </ol>
                </nav>
                <div class="col-auto">
                    <a href="{{ route('companies.index') }}" class="btn btn-outline-primary me-3">← Volver</a>
                </div>
            </div>
            <div class="col">
                <h2 class="page-title">Empresa: <span class="text-primary">{{ $company->company_name }}</span></h2>
            </div>
        </div>

        <div class="card shadow w-75">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="card-title">Detalles de la Empresa</h3>
                <a href="{{ route('companies.employees.index', $company) }}" class="btn btn-primary me-3">Ver empleados</a>
            </div>
            <div class="card-body row">
                <div class="col-md-6 mb-3"><strong>Razón social:</strong> {{ $company->denomination }}</div>
                <div class="col-md-6 mb-3"><strong>CUIT:</strong> {{ $company->cuit }}</div>
                <div class="col-md-6 mb-3"><strong>Nombre de fantasía:</strong> {{ $company->company_name }}</div>
                <div class="col-md-6 mb-3"><strong>Sector:</strong> {{ $company->sector }}</div>
                <div class="col-md-6 mb-3"><strong>Entidad:</strong> {{ $company->entity->name }}</div>
                <div class="col-md-6 mb-3"><strong>Rubro:</strong> {{ $company->company_category }}</div>
                <div class="col-md-6 mb-3"><strong>Ámbito:</strong> {{ $company->scope }}</div>
                <div class="col-md-6 mb-3"><strong>Calle:</strong> {{ $company->street }}</div>
                <div class="col-md-6 mb-3"><strong>Número:</strong> {{ $company->number }}</div>
                <div class="col-md-6 mb-3"><strong>Ciudad:</strong> {{ $company->city->name }}</div>
            </div>
        </div>
 {{-- NUEVA TARJETA: Documentos del Contrato Marco --}}
        @if (isset($frameworkAgreement) && $frameworkAgreement)
            <div class="card shadow w-75">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title">📁 Documentos del Convenio Marco</h4>
                        </div>
                <div class="card-body">
                    <p class="text-muted">Archivos adjuntos relacionados con el Convenio Marco (ID: {{ $frameworkAgreement->id }}).</p>
                    
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        
                        {{-- Documento 1: Constancia de AFIP --}}
                        @if ($frameworkAgreement->url_certificate_afip)
                            <a href="{{ route('contract.download.document', ['contract' => $frameworkAgreement->id, 'type' => 'afip']) }}" 
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download me-1"></i> Constancia AFIP
                            </a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary" disabled>Constancia AFIP (N/A)</button>
                        @endif

                        {{-- Documento 2: Estatuto de confirmación --}}
                        @if ($frameworkAgreement->url_statute)
                            <a href="{{ route('contract.download.document', ['contract' => $frameworkAgreement->id, 'type' => 'estatuto']) }}" 
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download me-1"></i> Estatuto
                            </a>
                        @else
                            <button class="btn btn-sm btn-sm btn-outline-secondary" disabled>Estatuto (N/A)</button>
                        @endif

                        {{-- Documento 3: Asignación de autoridades --}}
                        @if ($frameworkAgreement->url_assignment_authorities)
                            <a href="{{ route('contract.download.document', ['contract' => $frameworkAgreement->id, 'type' => 'autoridades']) }}" 
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download me-1"></i> Asignación Autoridades
                            </a>
                        @else
                            <button class="btn btn-sm btn-sm btn-outline-secondary" disabled>Autoridades (N/A)</button>
                        @endif
                        
                    </div>
                </div>
            </div>
        @else
             {{-- Mensaje si no hay contrato marco asociado --}}
            <div class="card shadow w-75">
                <div class="card-body">
                     <p class="alert alert-info mb-0">Esta empresa no tiene un Convenio Marco asociado o no está disponible.</p>
                </div>
            </div>
        @endif
        
    </div>
@endsection
