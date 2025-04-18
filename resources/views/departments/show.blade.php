<!-- resources/views/departments/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item"><span class="text-muted">Gestión Académica</span></li>
                        <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departamentos</a></li>
                        <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">{{$department->name }}</li>
                    </ol>
                </nav>
                <div class="col-auto">
                    <a href="{{ route('careers.index') }}" class="btn btn-outline-primary">← Volver</a>
                </div>
            </div>
            <div class="col">
                <h2 class="page-title">Departamento: <span class="text-primary">{{ $department->name }}</span></h2>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex align-items-center">
                <h4 class="fw-bold mb-2">Director del Departamento - </h4>
                <h3 class="ms-3 mb-2 ">{{ $department->teacher->lastname }} {{ $department->teacher->name }}</h3>
            </div>
        </div>

        <h4 class="fw-bold mb-3">Carreras Asociadas</h4>

        @if ($careersBelongsToDepartment->isEmpty())
            <p class="text-muted">No hay carreras asociadas a este departamento.</p>
        @else
            <div class="row">
                @foreach ($careersBelongsToDepartment as $career)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $career->name }}</h5>
                                <p class="mb-1"><strong>Coordinador:</strong> {{ $career->teacher->lastname }} {{ $career->teacher->name }}</p>
                                <p class="mb-2"><strong>Docentes a cargo:</strong></p>
                                @if ($career->teachers->isEmpty())
                                    <p class="text-muted ms-3 mb-0">Sin docentes asignados.</p>
                                @else
                                    <ul class="ms-4">
                                        @foreach ($career->teachers as $teacher)
                                            <li>{{ $teacher->lastname }} {{ $teacher->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
