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

        <!-- Incluir el formulario -->
        <div id="formEmpresaSeleccionada">
            @include('specificResidenceAgreement.formCreate')
        </div>
    </div>
@endsection
