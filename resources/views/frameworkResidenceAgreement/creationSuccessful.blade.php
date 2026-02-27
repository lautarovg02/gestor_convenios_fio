@extends('layouts.app')

@vite('resources\css\form_convenios\creationSuccessful.css')

@section('content')
    <div class="container">

        <div class="containerTextAndButton">
            <h1>¡Convenio generado con éxito!</h1>

            <div class="mt-4 flex-row align-items-center justify-content-center">
                <a href="{{ route('frameworkResidenceAgreement.download', ['path' => $relativePath, 'file' => $nombreArchivo]) }}"
                    class="button btn btn-primary">
                    Descargar Convenio
                </a>

                <a href="{{ route('agreements.index') }}" class="button btn btn-primary">
                    Ver Convenios
                </a>

            </div>
        </div>
    </div>
@endsection
