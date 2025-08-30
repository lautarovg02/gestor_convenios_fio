@extends('layouts.app') 

@section('content')
<div class="container-xl">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Contratos de la empresa: {{ $company->denomination }}</h3>
                    <a href="{{ route('companies.show', $company) }}" class="btn btn-outline-primary">
                        ← Volver a la Empresa
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if ($contracts->isEmpty())
                         <div class="alert alert-info mt-4">
                            No hay contratos registrados para esta empresa.
                        </div>
                        
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo de Convenio</th>
                                        <th>Estado</th>
                                        <th>Fecha de Creación</th>
                                        <th>Fecha de Firma</th>
                                        <th>Secretario</th>
                                        <th>Contacto</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contracts as $contract)
                                        <tr>
                                            <td>{{ $contract->id }}</td>
                                            <td>{{ $contract->typeFrameworkAgreement->type ?? 'N/A' }}</td>
                                            <td>
                                                @if ($contract->status)
                                                    <span class="badge" style="background-color: {{ $contract->status->color ?? 'gray' }}">
                                                        {{ $contract->status->status }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Sin estado</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($contract->creation_date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($contract->signing_date)->format('d/m/Y') }}</td>
                                            <td>{{ $contract->secretary->user_name ?? 'N/A' }}</td>
                                            <td>{{ $contract->contactEmployee->name ?? 'N/A' }}</td>
                                           
                                            <td class="text-end">
                                                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-info">Ver</a>
                                                <a href="{{ route('contracts.edit', $contract) }}"class="btn btn-primary btn-sm">Editar</a>
                                                <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este contrato?');">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection