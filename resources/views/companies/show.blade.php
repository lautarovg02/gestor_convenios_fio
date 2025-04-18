@extends('layouts.app')

@section('content')
<div class="container-xl mt-4 mb-5">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('companies.index') }}"  class="btn btn-outline-primary">← Volver</a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white">
            <h3 class="card-title">Detalles de la Empresa</h3>
        </div>
        <div class="card-body row">
            <div class="col-md-6 mb-3"><strong>Razón social:</strong> {{ $company->denomination }}</div>
            <div class="col-md-6 mb-3"><strong>CUIT:</strong> {{ $company->cuit }}</div>
            <div class="col-md-6 mb-3"><strong>Nombre de fantasía:</strong> {{ $company->company_name }}</div>
            <div class="col-md-6 mb-3"><strong>Sector:</strong> {{ $company->sector }}</div>
            <div class="col-md-6 mb-3"><strong>Entidad:</strong> {{ $company->entity->name }}</div>
            <div class="col-md-6 mb-3"><strong>Rubro:</strong> {{ $company->company_category }}</div>
            <div class="col-md-6 mb-3"><strong>Ámbito:</strong> {{ $company->scope }}</div>
            <div class="col-md-6 mb-3"><strong>Calle:</strong> {{ $company->street }}</div>
            <div class="col-md-6 mb-3"><strong>Número:</strong> {{ $company->number }}</div>
            <div class="col-md-6 mb-3"><strong>Ciudad:</strong> {{ $company->city->name }}</div>
        </div>
    </div>
</div>
@endsection
