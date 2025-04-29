@extends('layouts.app')

@section('title', 'Empleados - ' . $company->company_name)

@section('content')
    <div class=" d-flex flex-column container-xl mt-4 mb-5 justify-content-center align-items-center">
        <div class=" w-75 row justify-content-between align-items-center mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item"><a href="{{ route('companies.index', $company->id) }}">Empresas</a></li>
                        <li class="breadcrumb-item active text-decoration-underline" aria-current="page">
                            <a href="{{ route('companies.show', $company->id) }}">{{ $company->company_name }}</a>
                        </li>
                        <li class="breadcrumb-item fw-bold text-decoration-underline"> Empleados</li>
                    </ol>
                </nav>
                <div class="col-auto">
                    <a href="{{ route('companies.show', $company->id) }}" class="btn btn-outline-primary me-3">← Volver</a>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-1">Gestión de Empleados - {{ $company->company_name }}</h4>
            </div>
        </div>
        @unless ($employees->isEmpty())
            <div class="w-75 table-responsive rounded shadow-sm table-scrollable-container">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="col-max-width">Nombre</th>
                            <th class="col-max-width">Apellido</th>
                            <th class="col-max-width">DNI</th>
                            <th class="col-max-width">Cargo</th>
                            <th class="col-max-width">Email</th>
                            <th class="col-max-width">Representante</th>
                            <th class="col-max-width">Celular</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td class="col-max-width text-truncate" title="{{ $employee->name }}">
                                    {{ $employee->name }}
                                </td>
                                <td class="col-max-width text-truncate" title="{{ $employee->lastname }}">
                                    {{ $employee->lastname }}
                                </td>
                                <td class="col-max-width text-truncate" title="{{ $employee->dni }}">
                                    {{ $employee->dni }}
                                </td>
                                <td class="col-max-width text-truncate" title="{{ $employee->position }}">
                                    {{ $employee->position }}
                                </td>
                                <td class="col-max-width text-truncate" title="{{ $employee->email }}">
                                    {{ $employee->email }}
                                </td>
                                <td class="fs-6">
                                    @if ($employee->is_represent)
                                        <span class="badge bg-success">Es representante</span>
                                    @else
                                        <span class="badge bg-secondary">No es representante</span>
                                    @endif
                                </td>
                                <td class="col-max-width text-truncate">
                                    @if ($employee->phones->isNotEmpty())
                                        @foreach ($employee->phones as $phone)
                                        <div title="{{ $phone->number }}">{{ $phone->number }}</div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Sin número</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-primary btn-sm">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mt-4">No hay empleados registrados para esta empresa.</div>
        @endunless

    </div>
@endsection
