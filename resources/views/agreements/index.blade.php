@extends('layouts.app')

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
                        <li class="breadcrumb-item fw-bold text-decoration-underline">Convenios</li>
                    </ol>
                </nav>
                <div class="col-auto">
                    <a href="{{ route('companies.show', $company->id) }}" class="btn btn-outline-primary me-3">← Volver</a>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-1">Gestión de Convenios - {{ $company->company_name }}</h4>
            </div>
        </div>
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        @if (session('success'))
            <div id="flash-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (!$agreements->isEmpty())
            <div class="w-75 table-responsive rounded shadow-sm table-scrollable-container">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="col-max-width">Tipo de convenio</th>
                            <th class="col-max-width">Fecha de inicio</th>
                            <th class="col-max-width">Fecha de finalizacion</th>
                            <th class="col-max-width">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agreements as $agreement)
                            <tr>
                                <td class="col-max-width text-truncate">
                                    {{ $agreement->typeFrameworkAgreement->type}}
                                </td>
                                <td class="col-max-width text-truncate">
                                    {{ $agreement->creation_date}}
                                </td>
                                <td class="col-max-width text-truncate">
                                     @if($agreement->status->status === 'Finalizado')
                                         {{ $agreement->updated_at->format('d/m/Y') }}
                                     @else
                                         -
                                     @endif
                                 </td>
                                <td class="col-max-width text-truncate">
                                    {{ $agreement->status->status}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mt-4">No hay convenios registrados para esta empresa.</div>
        @endunless

</div>
@endsection
