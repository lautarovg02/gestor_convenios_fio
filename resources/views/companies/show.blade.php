<!-- resources/views/companies/show.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">

                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('companies.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detalles de la Empresa</h3>
                        </div>
                        <div class="card-body">

<div class="form-group">
<strong>Denominación:</strong>
{{ $company->denomination }}
</div>
<div class="form-group">
<strong>Cuit:</strong>
{{ $company->cuit }}
</div>
<div class="form-group">
<strong>Nombre de la empresa:</strong>
{{ $company->company_name }}
</div>
<div class="form-group">
<strong>Sector:</strong>
{{ $company->sector }}
</div>
<div class="form-group">
<strong>Entidad:</strong>
{{ $company->entity->name }}
</div>
<div class="form-group">
<strong>Rubro:</strong>
{{ $company->company_category }}
</div>
<div class="form-group">
<strong>Ámbito:</strong>
{{ $company->scope }}
</div>
<div class="form-group">
<strong>Calle:</strong>
{{ $company->street }}
</div>
<div class="form-group">
<strong>Número:</strong>
{{ $company->number }}
</div>
<div class="form-group">
<strong>Ciudad:</strong>
{{ $company->city->name}}
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

