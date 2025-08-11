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

        @if (!session('empresaSeleccionada') && session('existeAcuerdoConEmpresa'))
            <div class="alert alert-warning">
                <span>La empresa selecionada ya tiene un acuerdo individual de residencia creado</span>
            </div>
        @endif

        <!-- Incluir el formulario -->
        <div id="formEmpresaSeleccionada">
            @include('specificResidenceAgreement.formCreate')
        </div>
    </div>
@endsection
