@extends('layouts.app')

@vite('resources/css/form_convenios/formCreateAgreement.css')

@section('content')
<div class="container mt-4">

   <h2 class="text-center mb-4 title">CREAR CONVENIO INDIVIDUAL DE PASANTÍA</h2>

    {{-- Sección de selección de empresa --}}
    <div class="card mb-4 shadow-sm containerButtonSearchCompany">
        <div class="card-body">
            <h4 class="card-title">Seleccionar empresa</h4>
            <p class="card-text text-muted">Para continuar, seleccioná la empresa con convenio marco de pasantía.</p>

            <form action="{{ route('empresa.seleccionar') }}" method="POST" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label for="companySelect" class="form-label fw-semibold">Empresa</label>
                    <select id="companySelect" name="company_id" class="form-select" required>
                        <option value="">-- Seleccionar empresa --</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">
                                {{ $company->denomination }} (CUIT: {{ $company->cuit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Confirmar</button>
            </form>
        </div>
    </div>

</div>
@endsection



