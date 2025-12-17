@extends('layouts.app')

@section('title', 'Detalle de Convenio')

@section('content')
    <div class="d-flex flex-column container-xl mt-4 mb-5 justify-content-center align-items-center">
        <div class="w-75 row justify-content-between align-items-center mb-4">
            <div class="d-flex justify-content-between align-items-center w-100">
                {{-- Breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item"><a href="{{ route('agreements.index') }}">Convenios</a></li>
                        <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">
                            {{ $agreement->typeFrameworkAgreement->type ?? 'Convenio sin Tipo' }}</li>
                    </ol>
                </nav>
                <div class="col-auto">
                    {{-- Botón para volver al índice --}}
                    <a href="{{ route('agreements.index') }}" class="btn btn-outline-primary me-3">← Volver</a>
                </div>
            </div>
            <div class="col-12 mt-3">
                {{-- Título de la página --}}
                <h2 class="page-title">Convenio: 
                    <span class="text-primary">{{ $agreement->company->company_name ?? 'N/A' }}</span> 
                </h2>
            </div>
        </div>

        {{-- Tarjeta de Detalles del Convenio --}}
        <div class="card shadow w-75 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="card-title">Detalles del Convenio</h3>
            </div>
            <div class="card-body row">
                
                <div class="col-md-6 mb-3"><strong>Tipo de Convenio:</strong> {{ $agreement->typeFrameworkAgreement->type ?? 'N/A' }}</div>
                <div class="col-md-6 mb-3"><strong>Estado:</strong> <span class="badge bg-info text-dark">{{ $agreement->status->status ?? 'N/A' }}</span></div>
                
                <hr class="my-2">
                
                {{-- Información de la Empresa --}}
                <h5 class="mt-3 mb-3 text-secondary">Información de la Empresa</h5>
                @if ($agreement->company)
                    <div class="col-md-6 mb-3"><strong>Empresa (Razón Social):</strong> <a href="{{ route('companies.show', $agreement->company) }}">{{ $agreement->company->denomination }}</a></div>
                    <div class="col-md-6 mb-3"><strong>CUIT:</strong> {{ $agreement->company->cuit }}</div>
                @else
                    <div class="col-12 mb-3 text-danger">No hay empresa asociada.</div>
                @endif
                
                <hr class="my-2">

                {{-- Firmantes y Contactos --}}
                <h5 class="mt-3 mb-3 text-secondary">Firmantes y Responsables</h5>
                <div class="col-md-6 mb-3">
                    <strong>Rector (FIO):</strong> 
                    {{ $agreement->rectorTeacher->lastname ?? 'N/A' }}, {{ $agreement->rectorTeacher->name ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Secretario (FIO):</strong> 
                    {{-- Acceder al nombre a través de user name ya que secretary no tiene lastname --}}
                    @if ($agreement->secretary && $agreement->secretary->user)
                        {{ $agreement->secretary->user->name }} 
                    @else
                        N/A
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Docente Responsable:</strong> 
                    {{ $agreement->teacher->lastname ?? 'N/A' }}, {{ $agreement->teacher->name ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Representante de la Empresa:</strong> 
                    @if($agreement->representativeEmployee)
                        {{ $agreement->representativeEmployee->lastname }}, {{ $agreement->representativeEmployee->name }}
                    @else
                        N/A
                    @endif
                </div>
                 <div class="col-md-6 mb-3">
                    <strong>Empleado de Contacto:</strong> 
                    @if($agreement->contactEmployee)
                        {{ $agreement->contactEmployee->lastname }}, {{ $agreement->contactEmployee->name }}
                    @else
                        N/A
                    @endif
                </div>

                <hr class="my-2">

                {{-- Fechas y Documentos --}}
                <h5 class="mt-3 mb-3 text-secondary">Fechas y Documentación</h5>
                <div class="col-md-6 mb-3"><strong>Fecha de Creación:</strong> {{ \Carbon\Carbon::parse($agreement->creation_date)->format('d/m/Y') }}</div>
                <div class="col-md-6 mb-3"><strong>Fecha de Firma:</strong> {{ $agreement->signing_date ? \Carbon\Carbon::parse($agreement->signing_date)->format('d/m/Y') : 'Pendiente' }}</div>

                <div class="col-12 mb-3">
                    <strong class="d-block mb-1">Documentos Adjuntos:</strong>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Certificado AFIP
                            @if($agreement->url_certificate_afip)
                                <a href="{{route('contract.download.document', ['contract' => $agreement->id, 'type' => 'afip'])}}" target="_blank" class="btn btn-sm btn-outline-success">Descargar Archivo</a>
                            @else
                                <span class="text-muted">No adjunto</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Estatuto
                            @if($agreement->url_statute)
                                <a href="{{route('contract.download.document', ['contract' => $agreement->id, 'type' => 'estatuto'])}}" target="_blank" class="btn btn-sm btn-outline-success">Descargar Archivo</a>
                            @else
                                <span class="text-muted">No adjunto</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Asignación de Autoridades
                            @if($agreement->url_assignment_authorities)
                                <a href="{{route('contract.download.document', ['contract' => $agreement->id, 'type' => 'autoridades'])}}" target="_blank" class="btn btn-sm btn-outline-success">Descargar Archivo</a>
                            @else
                                <span class="text-muted">No adjunto</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection