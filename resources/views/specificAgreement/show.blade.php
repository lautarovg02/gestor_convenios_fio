@extends('layouts.app')

@section('title', 'Detalle de Convenio Específico')

@section('content')
<div class="d-flex flex-column container-xl mt-4 mb-5 justify-content-center align-items-center">
    <div class="w-75 row justify-content-between align-items-center mb-4">
        <div class="d-flex justify-content-between align-items-center w-100">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="{{ route('agreements.index') }}">Convenios</a></li>
                    <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Convenio Específico</li>
                </ol>
            </nav>
            <div class="col-auto">
                <a href="{{ route('agreements.index') }}" class="btn btn-outline-primary me-3">← Volver</a>
            </div>
        </div>
        <div class="col-12 mt-3">
            <h2 class="page-title">Convenio Específico:
                <span class="text-primary">{{ $specific->contract->company->company_name ?? 'N/A' }}</span>
            </h2>
        </div>
    </div>

    {{-- Estado y Tipo --}}
    <div class="card shadow w-75 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Detalles del Convenio Específico</h3>
            <span class="badge bg-info text-dark fs-6">{{ $specific->status->status ?? 'Sin Estado' }}</span>
        </div>
        <div class="card-body row">

            <h5 class="mt-2 mb-3 text-secondary">Información General</h5>
            <div class="col-md-6 mb-3"><strong>Tipo:</strong> Convenio Específico (Particular)</div>
            <div class="col-md-6 mb-3"><strong>Fecha de Firma:</strong>
                {{ $specific->signing_date ? \Carbon\Carbon::parse($specific->signing_date)->format('d/m/Y') : 'Pendiente' }}
            </div>
            <div class="col-12 mb-3"><strong>Objetivo:</strong> {{ $specific->objective ?? 'N/A' }}</div>
            <div class="col-12 mb-3"><strong>Compromisos:</strong> {{ $specific->commitment_parties ?? 'N/A' }}</div>
            <div class="col-md-6 mb-3"><strong>Responsable de Control (Empresa):</strong> {{ $specific->responsable_control_company ?? 'N/A' }}</div>
            <div class="col-md-6 mb-3"><strong>Responsable de Control (FIO):</strong> {{ $specific->responsable_control_fio ?? 'N/A' }}</div>

            <hr class="my-2">

            {{-- Información del Convenio Marco Padre --}}
            <h5 class="mt-3 mb-3 text-secondary">Convenio Marco Asociado</h5>
            @if ($specific->contract)
                <div class="col-md-6 mb-3">
                    <strong>Empresa (Razón Social):</strong>
                    <a href="{{ route('companies.show', $specific->contract->company) }}">{{ $specific->contract->company->denomination ?? 'N/A' }}</a>
                </div>
                <div class="col-md-6 mb-3"><strong>CUIT:</strong> {{ $specific->contract->company->cuit ?? 'N/A' }}</div>
                <div class="col-md-6 mb-3"><strong>Estado del Convenio Marco:</strong>
                    <span class="badge bg-secondary">{{ $specific->contract->status->status ?? 'N/A' }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="{{ route('agreements.show', $specific->contract->id) }}" class="btn btn-sm btn-outline-secondary">Ver Convenio Marco</a>
                </div>
            @else
                <div class="col-12 mb-3 text-danger">No hay convenio marco asociado.</div>
            @endif

            <hr class="my-2">

            {{-- Responsables --}}
            <h5 class="mt-3 mb-3 text-secondary">Responsables</h5>
            <div class="col-md-6 mb-3">
                <strong>Docente Responsable (FIO):</strong>
                {{ $specific->contract->teacher->lastname ?? 'N/A' }}, {{ $specific->contract->teacher->name ?? 'N/A' }}
            </div>
            <div class="col-md-6 mb-3">
                <strong>Secretario (FIO):</strong>
                {{ $specific->contract->secretary->user->name ?? 'N/A' }}
            </div>

            <hr class="my-2">

            {{-- Estudiantes --}}
            @if ($specific->students->isNotEmpty())
                <h5 class="mt-3 mb-3 text-secondary">Becarios</h5>
                @foreach ($specific->students as $student)
                    <div class="col-md-6 mb-2">
                        <i class="bi bi-person-fill me-1"></i>
                        {{ $student->last_name }}, {{ $student->name }} — DNI: {{ $student->dni }}
                    </div>
                @endforeach
            @endif

            <hr class="my-2">

            {{-- Documento --}}
            <h5 class="mt-3 mb-3 text-secondary">Documentación</h5>
            <div class="col-12">
                @if($specific->file)
                    <a href="{{ asset('storage/' . $specific->file) }}" target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-download me-1"></i> Descargar Convenio
                    </a>
                @else
                    <span class="text-muted">No hay documento adjunto.</span>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
