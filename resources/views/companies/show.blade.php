@extends('layouts.app')
@section('content')
<div class="container-xl my-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- 🔹 Encabezado con breadcrumb y botón --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('companies.index') }}">Empresas</a>
                        </li>
                        <li class="breadcrumb-item active fw-bold" aria-current="page">
                            {{ $company->company_name }}
                        </li>
                    </ol>
                </nav>
                <a href="{{ route('companies.index') }}" class="btn btn-outline-primary">
                    ← Volver
                </a>
            </div>

            {{-- 🔹 Título principal --}}
            <h2 class="mb-4">Empresa: <span class="text-primary">{{ $company->company_name }}</span></h2>

            {{-- 🔹 Card principal con secciones --}}
            <div class="card shadow-lg">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Detalles de la Empresa</h3>
                    <div class="d-flex gap-2">
                    <a href="{{ route('companies.employees.index', $company) }}" class="btn btn-info btn-sm">
                        Ver empleados
                    </a>
                    <a href="{{ route('contracts.show_for_company', $company) }}" class="btn btn-info btn-sm">
                        Ver contratos
                    </a>
                     </div>
                </div>

                <div class="card-body">
                    {{-- Sección: Detalles Generales --}}
                    <h4 class="mb-3 border-bottom pb-2">Datos Generales</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3"><strong>Razón social:</strong> {{ $company->denomination }}</div>
                        <div class="col-md-6 mb-3"><strong>CUIT:</strong> {{ $company->cuit }}</div>
                        <div class="col-md-6 mb-3"><strong>Nombre de fantasía:</strong> {{ $company->company_name }}</div>
                        <div class="col-md-6 mb-3"><strong>Sector:</strong> {{ $company->sector }}</div>
                        <div class="col-md-6 mb-3"><strong>Entidad:</strong> {{ $company->entity->name }}</div>
                        <div class="col-md-6 mb-3"><strong>Rubro:</strong> {{ $company->company_category }}</div>
                        <div class="col-md-6 mb-3"><strong>Ámbito:</strong> {{ $company->scope }}</div>
                    </div>

                    <hr class="my-4">

                    {{-- Sección: Dirección --}}
                    <h4 class="mb-3 border-bottom pb-2">Dirección</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3"><strong>Calle:</strong> {{ $company->street }}</div>
                        <div class="col-md-6 mb-3"><strong>Número:</strong> {{ $company->number }}</div>
                        <div class="col-md-6 mb-3"><strong>Ciudad:</strong> {{ $company->city->name }}</div>
                        <div class="col-md-6 mb-3"><strong>Código Postal:</strong> {{ $company->city->postal_code }}</div>
                        <div class="col-md-6 mb-3"><strong>Provincia:</strong> {{ $company->city->province->name }}</div>
                        <div class="col-md-6 mb-3"><strong>País:</strong> {{ $company->city->province->country->name }}</div>
                    </div>

                    <hr class="my-4">

                 {{-- Sección: Documentación --}}
<h4 class="mb-3 border-bottom pb-2">Documentación</h4>
<div class="row">
    {{-- Certificado AFIP --}}
    <div class="col-md-6 mb-3">
        <strong>Certificado AFIP:</strong><br>
        @if($company->url_certificate_afip)
            {{-- Now we call the new download route with the company slug and document type --}}
            <a href="{{ route('companies.download', ['slug' => $company->slug, 'documentType' => 'certificate_afip']) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">Ver certificado</a>
        @else
            <span class="badge bg-secondary mt-2">No disponible</span>
        @endif
    </div>

    {{-- Estatuto --}}
    <div class="col-md-6 mb-3">
        <strong>Estatuto:</strong><br>
        @if($company->url_statute)
            <a href="{{ route('companies.download', ['slug' => $company->slug, 'documentType' => 'statute']) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">Ver estatuto</a>
        @else
            <span class="badge bg-secondary mt-2">No disponible</span>
        @endif
    </div>

    {{-- Designación de autoridades --}}
    <div class="col-md-6 mb-3">
        <strong>Designación de autoridades:</strong><br>
        @if($company->url_assignment_authorities)
            <a href="{{ route('companies.download', ['slug' => $company->slug, 'documentType' => 'assignment_authorities']) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">Ver designación</a>
        @else
            <span class="badge bg-secondary mt-2">No disponible</span>
        @endif
    </div>

    {{-- Cláusula de confidencialidad --}}
    <div class="col-md-6 mb-3">
        <strong>Cláusula de confidencialidad:</strong><br>
        @if($company->has_confidentiality_clause)
            <span class="badge bg-success mt-2">Sí</span>
            @if($company->url_confidentiality_clause_file)
                <a href="{{ route('companies.download', ['slug' => $company->slug, 'documentType' => 'confidentiality_clause_file']) }}" target="_blank" class="btn btn-sm btn-outline-secondary ms-2 mt-2">Ver cláusula</a>
            @else
                 <span class="badge bg-warning mt-2 ms-2">Archivo no adjuntado</span>
            @endif
        @else
            <span class="badge bg-danger mt-2">No</span>
        @endif
    </div>
</div>

                {{-- Pie de Card con fechas --}}
                <div class="card-footer bg-light text-muted">
                    <div class="row">
                        <div class="col-md-6"><strong>Creada el:</strong> {{ $company->created_at->format('d/m/Y H:i') }}</div>
                        <div class="col-md-6"><strong>Última actualización:</strong> {{ $company->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection