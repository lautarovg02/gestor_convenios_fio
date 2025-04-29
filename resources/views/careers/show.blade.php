<!-- resources/views/companies/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl  content-with-footer-buffer"">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb" >
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item"><span class="text-muted">Gestión Académica</span></li>
                        <li class="breadcrumb-item"><a href="{{ route('careers.index') }}">Carreras</a></li>
                        <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">{{$career->name}}</li>
                    </ol>
                </nav>
                <div class="col-auto">
                    <a href="{{ route('careers.index') }}" class="btn btn-outline-primary">← Volver</a>
                </div>
            </div>
            <div class="col">
                <h2 class="page-title">Carrera: <span class="text-primary">{{ $career->name }}</span></h2>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <h5 class="fw-bold mb-2">Coordinador</h5>
                <p class="ms-3 mb-3">{{ $career->teacher->lastname }} {{ $career->teacher->name }}</p>

                <h5 class="fw-bold mb-2">Departamento</h5>
                <p class="ms-3 mb-1">{{ $career->department->name }}</p>

                <h6 class="fw-bold mt-3">Director del Departamento</h6>
                <p class="ms-3">{{ $career->department->teacher->lastname }} {{ $career->department->teacher->name }}</p>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Docentes de la Carrera</h5>
                @if ($teachersBelongsToCareer->isEmpty())
                    <p class="text-muted">No hay docentes asociados a esta carrera.</p>
                @else
                    <ul class="ms-3">
                        @foreach ($teachersBelongsToCareer as $teacher)
                            <li>{{ $teacher->lastname }} {{ $teacher->name }} <span class="text-muted">– DNI: {{ $teacher->dni }}</span></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
