@extends('layouts.app')

@section('content')
<div class="container-xl mt-4 content-with-footer-buffer">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                <li class="breadcrumb-item"><span class="text-muted">Gestión Académica</span></li>
                <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">Docentes</a></li>
                <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">{{ $teacher->name . ' ' . $teacher->lastname }}</li>
            </ol>
        </nav>
        <a href="{{ route('teachers.index') }}"  class="btn btn-outline-primary">← Volver</a>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <h3 class="card-title text-center">Detalles del Docente</h3>
        </div>
        <div class="card-body row">
            <div class="col-md-6 mb-3">
                <strong class="text-primary">Nombre:</strong> {{ $teacher->name . ' ' . $teacher->lastname }}
            </div>
            <div class="col-md-6 mb-3">
                <strong class="text-primary">DNI:</strong> {{ $teacher->dni }}
            </div>
            <div class="col-md-6 mb-3">
                <strong class="text-primary">CUIL:</strong> {{ $teacher->cuil ?? 'Sin información' }}
            </div>
            <div class="col-md-6 mb-3">
                <strong class="text-primary">Rol:</strong>
                @if ($teacher->role == 'Director')
                    <span class="badge bg-primary">Director</span>
                @elseif ($teacher->role == 'Coordinador')
                    <span class="badge bg-success">Coordinador</span>
                @elseif ($teacher->is_dean)
                    <span class="badge bg-success">Decano</span>
                @elseif ($teacher->is_rector)
                    <span class="badge bg-success">Rector</span>
                @else
                    <span class="badge bg-secondary">Sin Rol</span>
                @endif
            </div>
            <div class="col-12">
                <strong class="text-primary">Carreras:</strong>
                @foreach ($teacherWithCareers as $careers)
                    <div class="card p-2 my-2 border">
                        <p class="mb-1">{{ $careers->career }}</p>
                        <small class="text-muted">Relación: {{ $careers->relation }}</small>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer text-center">
            <small class="text-muted fw-bold">Información actualizada al {{ now()->format('d/m/Y') }}</small>
        </div>
    </div>
</div>
@endsection
