@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column container-xl mt-4 mb-5 justify-content-center align-items-center">

        {{-- Header: breadcrumb + volver --}}
        <div class="w-75 row justify-content-between align-items-center mb-4">
            <div class="d-flex justify-content-between align-items-center w-100">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('students.index') }}">Alumnos</a>
                        </li>
                        <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">
                            {{ $student->name }} {{ $student->last_name }}
                        </li>
                    </ol>
                </nav>
                <div class="col-auto">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-primary">← Volver</a>
                </div>
            </div>
        </div>

        {{-- Card de datos del alumno (similar a companies/show) --}}
        <div class="w-75">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Datos del alumno</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Nombre:</strong> {{ $student->name }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Apellido:</strong> {{ $student->last_name }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>DNI:</strong> {{ $student->dni }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>CUIL:</strong> {{ $student->cuil ?? '-' }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Carrera:</strong> {{ $student->career ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Email:</strong> {{ $student->email ?? '-' }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Teléfono:</strong> {{ $student->phone_numb ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Calle:</strong> {{ $student->street ?? '-' }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Número:</strong> {{ $student->number ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Ciudad:</strong> {{ $student->city ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
