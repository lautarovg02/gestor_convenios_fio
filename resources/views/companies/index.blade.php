<!-- resources/views/companies/index.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')
<div class="container mt-1">
    <div class="d-flex justify-content-between align-items-center mb-3">


        <!-- Botón "Crear Convenio" -->
        <a href="{{route('companies.create')}}" class="btn btn-secondary ">
            Crear empresa <i class="bi bi-plus"></i>
        </a>

        <!-- Barra de búsqueda -->
        <input type="text" class="form-control w-50" placeholder="Buscar empresas...">


    </div>

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
                    <a href="{{route('companies.show' , $company)}}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{route('companies.edit', $company)}}" class="btn btn-primary btn-sm">Editar</a>
                    <a href="#" class="btn btn-secondary btn-sm">Desvincular</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Mostrar enlaces de paginación -->
    <div class="d-flex justify-content-center">
        {{ $companies->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
