<!-- resources/views/companies/index.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')

<div class="container mt-1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="#" class="btn btn-secondary" onclick="event.preventDefault();">
            Crear Convenio <i class="bi bi-plus"></i>
        </a>

        <!-- Barra de búsqueda -->
        <form action="{{ route('companies.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Buscar empresas..." value="{{ request()->input('search') }}" style="min-width: 400px;">
            <button type="submit" class="btn btn-primary ms-2">Buscar</button>
        </form>
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
                <td>{!! highlightKeyword($company->denomination, request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->cuit, request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->company_name ?? 'N/A', request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->sector ?? 'N/A', request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->entity ?? 'N/A', request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->company_category ?? 'N/A', request()->input('search')) !!}</td>
                <td>{!! highlightKeyword($company->city->name ?? 'N/A', request()->input('search')) !!}</td>
                <td>
                    <a href="#" class="btn btn-info btn-sm">Ver</a>
                    <a href="#" class="btn btn-primary btn-sm">Editar</a>
                    <a href="#" class="btn btn-secondary btn-sm">Eliminar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $companies->appends(['search' => request()->input('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
