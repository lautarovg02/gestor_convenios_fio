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
                            Regresar
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
                            <h3 class="card-title">Detalles de la Compañía</h3>
                        </div>
                        <div class="card-body">

<div class="form-group">
<strong>Denomination:</strong>
{{ $company->denomination }}
</div>
<div class="form-group">
<strong>Cuit:</strong>
{{ $company->cuit }}
</div>
<div class="form-group">
<strong>Company Name:</strong>
{{ $company->company_name }}
</div>
<div class="form-group">
<strong>Sector:</strong>
{{ $company->sector }}
</div>
<div class="form-group">
<strong>Entity:</strong>
{{ $company->entity }}
</div>
<div class="form-group">
<strong>Company Category:</strong>
{{ $company->company_category }}
</div>
<div class="form-group">
<strong>Scope:</strong>
{{ $company->scope }}
</div>
<div class="form-group">
<strong>Street:</strong>
{{ $company->street }}
</div>
<div class="form-group">
<strong>Number:</strong>
{{ $company->number }}
</div>
<div class="form-group">
<strong>City Id:</strong>
{{ $company->city_id }}
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

