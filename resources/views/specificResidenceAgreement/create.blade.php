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

        <div class="containerButtonSearchCompany">

            <div class="containerTitleSelectCompany">
                <h3 class="titleSelectCompany">Seleccionar empresa</h3>
                <p id="info">Para poder continuar, es necesario que seleccione la empresa con la cual desea realizar el
                    acuerdo.</p>
            </div>
            <!-- Botón para abrir el modal -->
            <button type="button" class="btn btn-primary searchCompany" data-bs-toggle="modal" data-bs-target="#searchModal">
                Buscar empresas
            </button>
        </div>

        <!-- Modal para seleccionar empresa -->
        @include('specificResidenceAgreement.modal-select-company')

        <!-- Incluir el formulario -->
        <div id="formEmpresaSeleccionada" class="d-none">
            @include('specificResidenceAgreement.formCreate')
        </div>
    </div>
@endsection
