<!-- resources/views/teachers/show.blade.php -->
@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none m-2 ">
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
                            <small class="text-muted">Información actualizada al {{ now()->format('d/m/Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
