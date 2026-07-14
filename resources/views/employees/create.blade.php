@extends('layouts.app')

@section('title', 'Alta Empleado - ' . $company->company_name)

@section('content')
<div class="row row-deck row-cards content-with-footer-buffer">
    <div class="col-12">
        <div class="card shadow-sm rounded">
            <!-- Cabecera -->
            <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                <h3 >Alta de Empleado - {{ $company->company_name }}</h3>
                <nav aria-label="breadcrumb" class="ms-3 mt-3">
                    <ol class="breadcrumb bg-light p-2 rounded shadow-sm mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Empresas</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('companies.employees.index', $company->id) }}">Empleados</a></li>
                        <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Alta Empleado</li>
                    </ol>
                </nav>
                <a href="{{ route('companies.employees.index', $company->id) }}" class="btn btn-outline-primary">← Volver</a>
            </div>

            <!-- Cuerpo de la tarjeta -->
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('companies.employees.store', $company->id) }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label required-field fs-6 fw-bold">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label required-field fs-6 fw-bold">Apellido</label>
                            <input type="text" name="lastname" value="{{ old('lastname') }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label required-field fs-6 fw-bold">DNI</label>
                            <input type="number" name="dni" value="{{ old('dni') }}" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label required-field fs-6 fw-bold">CUIL</label>
                            <input type="number" name="cuil" value="{{ old('cuil') }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label required-field fs-6 fw-bold">Cargo</label>
                            <input type="text" name="position" value="{{ old('position') }}" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label fs-6 fw-bold">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_represent" value="1" class="form-check-input" id="representCheck">
                        <label for="representCheck" class="form-check-label fs-6 fw-bold">Es representante de la empresa</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Contacto</label>
                        <div id="phones-container">
                            <input type="number" name="phone" value="{{ old('phone') }}" class="form-control mb-2" placeholder="Número de contacto">
                        </div>
                    </div>

                    <div class="form-footer d-flex justify-content-end">
                        <a href="{{ route('companies.employees.index', $company->id) }}" class="btn btn-danger m-2">Cancelar</a>
                        <button type="submit" class="btn btn-success m-2">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @vite('resources/js/utils/errorMessageTime.js')
    @vite('resources/js/utils/toggleButtonAddPhone.js')
    @vite('resources/js/utils/buttonDeletePhoneNumber.js')
@endsection
