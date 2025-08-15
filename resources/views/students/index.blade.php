@extends('layouts.app')

@section('content')
<div class="container ">

    <!-- Header con Título y Botón -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h4 class="fw-bold mb-1">Gestión de Alumnos</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item text-muted">Gestión Académica</li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Alumnos</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Agregar Alumno
        </a>
    </div>

    <!-- Filtros (opcional) -->
    <div class="mb-4">
        @includeIf('students.filters')
    </div>

    <!-- Mensajes -->
    @if (Session::get('success'))
    <div class="alert alert-success">{{ Session::get('success') }}</div>
    @elseif (Session::get('error'))
    <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @elseif (isset($errorMessage))
    <div class="alert alert-warning">{{ $errorMessage }}</div>
    @elseif ($students->isEmpty())
    <div class="alert alert-info">¡La tabla de alumnos está vacía!</div>
    @endif

    <!-- Tabla de Alumnos -->
    @if (!$students->isEmpty())
    <div class="card shadow-sm rounded">
        <div class="table-responsive rounded shadow-sm table-scrollable-container">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th class="col-max-width">Nombre</th>
                        <th>DNI</th>
                        <th>CUIL</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Carrera</th>
                        <th class="text-center" style="width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>

                        <!-- Nombre del alumno -->
                        <td class="text-truncate col-max-width"
                            title="{{ $student->name . ' ' . $student->last_name }}">
                            {!! highlightKeyword($student->name, request('search')) !!}
                            {!! highlightKeyword($student->last_name, request('search')) !!}
                        </td>

                        <td>{{ $student->dni }}</td>
                        <td>{{ $student->cuil ?? 'N/A' }}</td>
                        <td>{{ $student->email ?? 'N/A' }}</td>
                        <td>{{ $student->phone_numb ?? 'N/A' }}</td>
                        <td>{{ $student->career ?? 'Sin carrera' }}</td>

                        <!-- Botones de acción -->
                        <td class="text-center">
                            <a href="{{ route('students.show', $student) }}"
                                class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('students.edit', $student) }}"
                                class="btn btn-primary btn-sm">Editar</a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-delete" data-entity-id="{{ $student->id }}"
                                data-entity-name="{{ $student->name . ' ' . $student->last_name }}"
                                data-entity-type="students">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
    @endif


    @include('layouts.modals.modal-delete')
    @include('layouts.modals.modal-loading')
    @vite('resources/js/utils/flashMessage.js')
    @vite('resources/js/modals/modalDelete.js')

</div>
@endsection