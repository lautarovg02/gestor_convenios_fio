@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('careers.create') }}" class="btn btn-secondary" onclick="">
            Agregar Carrera <i class="bi bi-plus"></i>
        </a>
    </div>

    <!-- Listado de carreras -->

    <h1>Aca va un listado de carreras</h1>
@endsection
