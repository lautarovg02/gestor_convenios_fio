{{-- resources/views/companies/show.blade.php --}}

@section('title', 'Detalle Empresa')

<x-app-layout>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h2 class="h5 mb-1">
          Empresa: <span class="text-primary">{{ $company->company_name }}</span>
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Empresas</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $company->company_name }}</li>
          </ol>
        </nav>
      </div>
      <div class="ms-3">
        <a href="{{ route('companies.index') }}" class="btn btn-outline-primary me-2">← Volver</a>
        <a href="{{ route('companies.employees.index', $company) }}" class="btn btn-primary">Ver empleados</a>
      </div>
    </div>
  </x-slot>

  <div class="container-xl mt-3 mb-5">

    <div class="card shadow">
      <div class="card-header bg-white">
        <h3 class="card-title mb-0">Detalles de la Empresa</h3>
      </div>
      <div class="card-body row">
        <div class="col-md-6 mb-3"><strong>Razón social:</strong> {{ $company->denomination }}</div>
        <div class="col-md-6 mb-3"><strong>CUIT:</strong> {{ $company->cuit }}</div>
        <div class="col-md-6 mb-3"><strong>Nombre de fantasía:</strong> {{ $company->company_name }}</div>
        <div class="col-md-6 mb-3"><strong>Sector:</strong> {{ $company->sector ?? 'N/A' }}</div>
        <div class="col-md-6 mb-3"><strong>Entidad:</strong> {{ $company->entity?->name ?? 'N/A' }}</div>
        <div class="col-md-6 mb-3"><strong>Rubro:</strong> {{ $company->company_category ?? 'N/A' }}</div>
        <div class="col-md-6 mb-3"><strong>Ámbito:</strong> {{ $company->scope ?? 'N/A' }}</div>
        <div class="col-md-6 mb-3"><strong>Calle:</strong> {{ $company->street ?? 'N/A' }}</div>
        <div class="col-md-6 mb-3"><strong>Número:</strong> {{ $company->number ?? 'N/A' }}</div>
        <div class="col-md-6 mb-3"><strong>Ciudad:</strong> {{ $company->city?->name ?? 'N/A' }}</div>
      </div>
    </div>
  </div>
</x-app-layout>
