@extends('layouts.app')

@vite('resources\css\form_convenios\creationSuccessful.css')

@section('content')
    <div class="container">

        <div class="containerTextAndButton">
            <h1>¡Convenio generado con éxito!</h1>
            <p>Podés descargar el documento generado a continuación:</p>

            <a href="{{ route('agreement.download', ['path' => $relativePath, 'file' => $nombreArchivo]) }}"
                class="button btn btn-primary">
                Descargar Convenio
            </a>

        </div>
    </div>
@endsection
