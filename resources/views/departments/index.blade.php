<!-- resources/views/departments/index.blade.php-->
@extends('layouts.app')

@section('content')
    <!-- Resto de tu vista para listar departamentos -->

    <div class="container mt-1">
        <!-- Botón agregar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('departments.create') }}" class="btn btn-success" onclick="">
                Agregar departamento <i class="bi bi-plus"></i>
            </a>
        </div>

        <!-- Mensajes de error o éxito al editar, eliminar o crear entidad -->
        @if (Session::get('success'))
            <div class="alert alert-success message-box">
                <p class="mb-0">{!! Session::get('success') !!}</p>
            </div>
        @elseif (Session::get('error'))
            <div class="alert alert-danger message-box">
                <p class="mb-0">{!! Session::get('error') !!}</p>
            </div>
        @endif

        <!-- Mostrar mensaje de sin resultados -->
        @if ($noResults)
            <div class="alert alert-warning text-center message-box">
                No se encontraron departamentos.
            </div>
        @else
            <!-- Tabla de resultados -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="max-width:500px" >Denominación</th>
                        <th >Director de Departamento</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                        <tr>
                            <td>{{ $department->id }}</td>
                            <td style="max-width:440px"  class=" text-truncate col-min-width col-max-width">{!! highlightKeyword($department->name, request()->input('search')) !!}</td>
                            <td class="  text-truncate col-min-width col-max-width">{!! highlightKeyword(
                                $department->teacher->lastname . ' ' . $department->teacher->name,
                                request()->input('search'),
                            ) !!}</td>
                            <td class=" text-center text-truncate col-min-width col-max-width">
                                <a href="{{ route('departments.show', $department) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('departments.edit', $department) }}"
                                    class="btn btn-primary btn-sm">Editar</a>
                                <button type="button" class="btn btn-danger btn-sm" data-entity-id="{{ $department->id }}"
                                    data-entity-name="{{ $department->name }}" data-entity-type="departments"
                                    data-bs-toggle="modal" data-bs-target="#modal-delete">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $departments->links('pagination::bootstrap-4') }}
            </div>
        @endif

        <!-- Modal -->
        @include('layouts/modals/modal-delete')

    </div>


    <!--Linkeamos el .js del modal al template utilizando Vite-->
    @vite('resources/js/modals/modalDelete.js')
@endsection
