@extends('layouts.app')

@section('title', 'Detalle de Acuerdo Individual de Pasantía')

@section('content')
<div class="d-flex flex-column container-xl mt-4 mb-5 justify-content-center align-items-center">
    <div class="w-75 row justify-content-between align-items-center mb-4">
        <div class="d-flex justify-content-between align-items-center w-100">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="{{ route('agreements.index') }}">Convenios</a></li>
                    <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Acuerdo Individual de Pasantía</li>
                </ol>
            </nav>
            <div class="col-auto">
                <a href="{{ route('agreements.index') }}" class="btn btn-outline-primary me-3">← Volver</a>
            </div>
        </div>
        <div class="col-12 mt-3">
            <h2 class="page-title">Acuerdo Individual de Pasantía:
                <span class="text-primary">{{ $agreement->contract->company->company_name ?? 'N/A' }}</span>
            </h2>
        </div>
    </div>

    {{-- Card Principal --}}
    <div class="card shadow w-75 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Detalles del Acuerdo</h3>
            <span class="badge bg-info text-dark fs-6">{{ $agreement->status->status ?? 'Sin Estado' }}</span>
        </div>
        <div class="card-body row">

            <h5 class="mt-2 mb-3 text-secondary">Información General</h5>
            <div class="col-md-6 mb-3"><strong>Área de Pasantía:</strong> {{ $agreement->area ?? 'N/A' }}</div>
            <div class="col-md-6 mb-3"><strong>Sitio/Asignación:</strong> {{ $agreement->assignment ?? 'N/A' }}</div>
            <div class="col-md-6 mb-3"><strong>Duración:</strong> {{ $agreement->months_quantity ?? 'N/A' }} meses</div>
            <div class="col-md-6 mb-3"><strong>Fecha de Inicio:</strong>
                {{ $agreement->internship_initial_date ? \Carbon\Carbon::parse($agreement->internship_initial_date)->format('d/m/Y') : 'Pendiente' }}
            </div>
            <div class="col-md-6 mb-3"><strong>Fecha de Firma:</strong>
                {{ $agreement->signing_date ? \Carbon\Carbon::parse($agreement->signing_date)->format('d/m/Y') : 'Pendiente' }}
            </div>
            <div class="col-12 mb-3"><strong>Tareas:</strong> {{ $agreement->task ?? 'N/A' }}</div>

            <hr class="my-2">

            {{-- Convenio Marco Padre --}}
            <h5 class="mt-3 mb-3 text-secondary">Convenio Marco Asociado</h5>
            @if ($agreement->contract)
                <div class="col-md-6 mb-3">
                    <strong>Empresa:</strong>
                    <a href="{{ route('companies.show', $agreement->contract->company) }}">{{ $agreement->contract->company->denomination ?? 'N/A' }}</a>
                </div>
                <div class="col-md-6 mb-3"><strong>CUIT:</strong> {{ $agreement->contract->company->cuit ?? 'N/A' }}</div>
                <div class="col-md-6 mb-3"><strong>Estado del Convenio Marco:</strong>
                    <span class="badge bg-secondary">{{ $agreement->contract->status->status ?? 'N/A' }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="{{ route('agreements.show', $agreement->contract->id) }}" class="btn btn-sm btn-outline-secondary">Ver Convenio Marco</a>
                </div>
            @else
                <div class="col-12 mb-3 text-danger">No hay convenio marco asociado.</div>
            @endif

            <hr class="my-2">

            {{-- Estudiante --}}
            <h5 class="mt-3 mb-3 text-secondary">Pasante</h5>
            @if ($agreement->student)
                <div class="col-md-6 mb-3">
                    <i class="bi bi-person-fill me-1"></i>
                    <strong>{{ $agreement->student->last_name }}, {{ $agreement->student->name }}</strong>
                </div>
                <div class="col-md-6 mb-3"><strong>DNI:</strong> {{ $agreement->student->dni }}</div>
                @if($agreement->student->email)
                    <div class="col-md-6 mb-3"><strong>Email:</strong> {{ $agreement->student->email }}</div>
                @endif
            @else
                <div class="col-12 mb-3 text-muted">No hay estudiante asociado.</div>
            @endif

            <hr class="my-2">

            {{-- Responsables --}}
            <h5 class="mt-3 mb-3 text-secondary">Responsables</h5>
            <div class="col-md-6 mb-3">
                <strong>Docente Responsable (FIO):</strong>
                {{ $agreement->contract->teacher->lastname ?? 'N/A' }}, {{ $agreement->contract->teacher->name ?? 'N/A' }}
            </div>
            <div class="col-md-6 mb-3">
                <strong>Secretario (FIO):</strong>
                {{ $agreement->contract->secretary->user->name ?? 'N/A' }}
            </div>

            <hr class="my-2">

            {{-- Documento --}}
            <h5 class="mt-3 mb-3 text-secondary">Documentación</h5>
            <div class="col-12">
                @if($agreement->file)
                    <a href="{{ route('individual-internship-agreements.download', $agreement->id) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-download me-1"></i> Descargar Acuerdo
                    </a>
                @else
                    <span class="text-muted">No hay documento adjunto.</span>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
