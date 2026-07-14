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
                <div class="col-md-6 mb-3"><strong>Categoría:</strong> {{ $company->company_category }}</div>
                <div class="col-md-6 mb-3"><strong>Rubro:</strong> {{ $company->rubro }}</div>
                <div class="col-md-6 mb-3"><strong>Dedicación:</strong> {{ $company->dedicacion }}</div>
                <div class="col-md-6 mb-3"><strong>Ámbito:</strong> {{ $company->scope }}</div>
                <div class="col-md-6 mb-3"><strong>Calle:</strong> {{ $company->street }}</div>
                <div class="col-md-6 mb-3"><strong>Número:</strong> {{ $company->number }}</div>
                <div class="col-md-6 mb-3"><strong>Ciudad:</strong> {{ $company->city->name }}</div>
            </div>
        </div>
 {{-- NUEVA TARJETA: Documentos del Contrato Marco --}}
        @if (isset($frameworkAgreements) && $frameworkAgreements->isNotEmpty())
            @foreach($frameworkAgreements as $frameworkAgreement)
            <div class="card shadow w-75 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title">📁 Documentos - {{ $frameworkAgreement->typeFrameworkAgreement->type ?? 'Convenio Marco' }}</h4>
                    <a href="{{ route('agreements.show', $frameworkAgreement->id) }}" class="btn btn-sm btn-primary">Ver detalle <i class="bi bi-eye"></i></a>
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
                            <button class="btn btn-sm btn-outline-secondary" disabled>Estatuto (N/A)</button>
                        @endif

                        {{-- Documento 3: Asignación de autoridades --}}
                        @if ($frameworkAgreement->url_assignment_authorities)
                            <a href="{{ route('contract.download.document', ['contract' => $frameworkAgreement->id, 'type' => 'autoridades']) }}" 
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download me-1"></i> Asignación Autoridades
                            </a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary" disabled>Autoridades (N/A)</button>
                        @endif
                        
                    </div>

                    {{-- Mostrar convenios derivados (hijos) dependiendo del tipo de Convenio Marco --}}
                    @if(
                        ($frameworkAgreement->type_framework_agreement_id == 3 && $frameworkAgreement->specifics->isNotEmpty()) || 
                        ($frameworkAgreement->type_framework_agreement_id == 2 && $frameworkAgreement->specificResidenceAgreements->isNotEmpty()) || 
                        ($frameworkAgreement->type_framework_agreement_id == 1 && $frameworkAgreement->individualIntershipAgreements->isNotEmpty())
                    )
                        <hr class="mt-4 mb-3">
                        <h5 class="fw-bold mb-3 text-secondary">Convenios Derivados</h5>
                        <ul class="list-group list-group-flush">
                            
                            {{-- Convenios Específicos para Convenio Marco Común (ID 3) --}}
                            @if($frameworkAgreement->type_framework_agreement_id == 3)
                                @foreach($frameworkAgreement->specifics as $specific)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>
                                            <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                            <strong>Convenio Específico</strong> (ID: {{ $specific->id }}) - Creado el {{ \Carbon\Carbon::parse($specific->created_at)->format('d/m/Y') }}
                                        </span>
                                        <a href="{{ route('specificAgreement.show', $specific->id) }}" class="btn btn-sm btn-outline-primary">Ver Detalle</a>
                                    </li>
                                @endforeach
                            @endif
                            
                            {{-- Acuerdos Específicos de Residencia para Convenio Marco de Residencia (ID 2) --}}
                            @if($frameworkAgreement->type_framework_agreement_id == 2)
                                @foreach($frameworkAgreement->specificResidenceAgreements as $residence)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>
                                            <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                            <strong>Acuerdo Específico de Residencia</strong> (ID: {{ $residence->id }}) - Alumno: {{ $residence->student->name ?? 'N/A' }} {{ $residence->student->lastname ?? '' }}
                                        </span>
                                        <a href="{{ route('specificResidenceAgreement.show', $residence->id) }}" class="btn btn-sm btn-outline-primary">Ver Detalle</a>
                                    </li>
                                @endforeach
                            @endif

                            {{-- Acuerdos Individuales de Pasantía para Convenio Marco de Pasantía (ID 1) --}}
                            @if($frameworkAgreement->type_framework_agreement_id == 1)
                                @foreach($frameworkAgreement->individualIntershipAgreements as $internship)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>
                                            <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                            <strong>Acuerdo Individual de Pasantía</strong> (ID: {{ $internship->id }}) - Alumno: {{ $internship->student->name ?? 'N/A' }} {{ $internship->student->lastname ?? '' }}
                                        </span>
                                        <a href="{{ route('individual-internship-agreements.show', $internship->id) }}" class="btn btn-sm btn-outline-primary">Ver Detalle</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
            @endforeach
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
