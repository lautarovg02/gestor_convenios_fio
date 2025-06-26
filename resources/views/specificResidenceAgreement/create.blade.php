@extends('layouts.app')

@vite('resources\css\form_convenios\createSpecificResidenceAgreement.css')
@vite('resources\css\form_convenios\formCreateAgreement.css')
@section('content')
    <div class="container mt-4">
        <h2 class=" title mb-3 text-center">CREAR ACUERDO INDIVIDUAL DE RESIDENCIA</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
@if (session('existeAcuerdo'))
    {{-- Mostrar alerta si ya existe un acuerdo --}}
    <div class="alert alert-warning" id="messageExistAgreement">
        Ya existe un acuerdo individual para esta empresa. Seleccione otra empresa para continuar.
    </div>

    {{-- Estado inicial: botón y modal --}}
    <div class="containerButtonSearchCompany">
        <div class="containerTitleSelectCompany">
            <h3 class="titleSelectCompany">Seleccionar empresa</h3>
            <p id="info">Para poder continuar, es necesario que seleccione la empresa con la cual desea realizar el acuerdo.</p>
        </div>
        <button type="button" class="btn btn-primary searchCompany" data-bs-toggle="modal" data-bs-target="#searchModal">
            Buscar empresas
        </button>
    </div>

    @include('specificResidenceAgreement.modal-select-company')

@elseif (session('empresaSeleccionada') || $errors->any())
    {{-- Si se seleccionó una empresa o hay errores, mostrar el formulario --}}
    <div id="formEmpresaSeleccionada">
        @include('specificResidenceAgreement.formCreate')
    </div>
@else
    {{-- Estado inicial sin errores ni empresa seleccionada --}}
    <div class="containerButtonSearchCompany">
        <div class="containerTitleSelectCompany">
            <h3 class="titleSelectCompany">Seleccionar empresa</h3>
            <p id="info">Para poder continuar, es necesario que seleccione la empresa con la cual desea realizar el acuerdo.</p>
        </div>
        <button type="button" class="btn btn-primary searchCompany" data-bs-toggle="modal" data-bs-target="#searchModal">
            Buscar empresas
        </button>
    </div>

    @include('specificResidenceAgreement.modal-select-company')
@endif
        <!-- Incluir el formulario -->
        <div id="formEmpresaSeleccionada" class="d-none">
            @include('specificResidenceAgreement.formCreate')
        </div>
    </div>
@endsection
