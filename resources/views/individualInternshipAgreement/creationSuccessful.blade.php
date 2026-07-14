@extends('layouts.app')

@vite('resources/css/form_convenios/creationSuccessful.css')

@section('content')
    <div class="container">
        <div class="containerTextAndButton">
            <h1>¡Convenio Individual de Pasantía generado con éxito!</h1>

            <div class="mt-4 flex-row align-items-center justify-content-center">
                <a href="{{ route('individual-internship-agreements.download', $agreement->id) }}"
                    class="button btn btn-primary">
                    <i class="bi bi-download me-1"></i> Descargar Convenio
                </a>

                <a href="{{ route('agreements.index') }}" class="button btn btn-primary ms-2">
                    <i class="bi bi-list me-1"></i> Ver Convenios
                </a>
            </div>
        </div>
    </div>
@endsection
