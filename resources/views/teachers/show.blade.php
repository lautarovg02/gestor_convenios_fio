<!-- resources/views/teachers/show.blade.php -->
@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 ">
                <!-- Page title actions -->
                <div class="col-12 align-items-center d-flex ms-auto justify-content-between d-print-none m-2 ">
                    <nav aria-label="breadcrumb" class="ms-3 mt-3">
                        <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                            <li class="breadcrumb-item">
                                <span class="text-muted">Gestión Académica</span>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('teachers.index') }}">Docentes</a>
                            </li>
                            <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                    <div class="btn-list">
                        <a href="{{ route('teachers.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            Regresar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body m-3">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title text-center">Detalles del Docente</h3>
                        </div>
                        <div class="card-body">
                            <div class="details-container">
                                <div class="detail-item mb-3">
                                    <h5 class="mb-1 text-primary">Nombre:</h5>
                                    <p>{{ $teacher->name . ' ' . $teacher->lastname }}</p>
                                </div>
                                <div class="detail-item mb-3">
                                    <h5 class="mb-1 text-primary">DNI:</h5>
                                    <p>{{ $teacher->dni }}</p>
                                </div>
                                <div class="detail-item mb-3">
                                    <h5 class="mb-1 text-primary">CUIL:</h5>
                                    <p>{{ $teacher->cuil ?? 'Sin información' }}</p>
                                </div>
                                <div class="detail-item mb-3">
                                    <h5 class="mb-1 text-primary">Rol:</h5>
                                    @if ($teacher->role == 'Director')
                                        <span class="badge bg-primary">Director</span>
                                    @elseif ($teacher->role == 'Coordinador')
                                        <span class="badge bg-success">Coordinador</span>
                                    @elseif ($teacher->is_dean)
                                        <span class="badge {{ $teacher->is_dean ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $teacher->is_dean ? 'Decano' : 'No es Decano' }}
                                        </span>
                                    @elseif ($teacher->is_rector)
                                        <span class="badge {{ $teacher->is_rector ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $teacher->is_rector ? 'Rector' : 'No es Rector' }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Sin Rol</span>
                                    @endif
                                </div>
                                <div class="detail-item mb-3">
                                    <h5 class="mb-1 text-primary">Carrera:</h5>
                                    @foreach ($teacherWithCareers as $careers)
                                        <p class="mb-1">{{ $careers->career . ' - ' . $careers->relation }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <small class="text-muted fw-bold">Información actualizada al
                                {{ now()->format('d/m/Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
