<!-- resources/views/companies/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Listado de Compañías</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Denominación</th>
                <th>CUIT</th>
                <th>Nombre de la Compañía</th>
                <th>Sector</th>
                <th>Entidad</th>
                <th>Categoría</th>
                <th>Ciudad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->id }}</td>
                <td>{{ $company->denomination }}</td>
                <td>{{ $company->cuit }}</td>
                <td>{{ $company->company_name ?? 'N/A' }}</td>
                <td>{{ $company->sector ?? 'N/A' }}</td>
                <td>{{ $company->entity ?? 'N/A' }}</td>
                <td>{{ $company->company_category ?? 'N/A' }}</td>
                <td>{{ $company->city->name ?? 'N/A' }}</td>
                <td>
                    <a href="#" class="btn btn-primary btn-sm">Ver</a>
                    <a href="#" class="btn btn-warning btn-sm">Editar</a>
                    <a href="#" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Mostrar enlaces de paginación -->
    <div class="d-flex justify-content-center">
        {{ $companies->links() }}
    </div>
</div>
@endsection