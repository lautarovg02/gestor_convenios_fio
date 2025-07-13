@extends('layouts.app')

@section('content')
<div class="container mt-5 text-center">
    <h2>Convenio individual de pasantía guardado con éxito.</h2>
    <p class="mt-4">
        <a href="{{ route('individualInternshipAgreement.download', ['id' => $agreement->id]) }}" class="btn btn-primary">
            Descargar convenio
        </a>
    </p>
</div>
@endsection
