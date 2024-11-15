@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('careers.create') }}" class="btn btn-secondary" onclick="">
            Agregar Carrera <i class="bi bi-plus"></i>
        </a>
    </div>

    <!--- Mensajes de error o success al editar, eliminar o crear entidad --->
    @if (Session::get('success'))
        <div class="alert alert-success">
            <p class="mb-1">{!! Session::get('success') !!}</p>
        </div>
    @elseif (Session::get('error'))
        <div class="alert alert-danger">
            <p class="mb-1">{!! Session::get('error') !!}</p>
        </div>
    @endif
    <!-- Listado de carreras -->
    <h1>Aca va un listado de carreras</h1>
@endsection
