{{-- resources/views/careers/show.blade.php --}}

@section('title', 'Carreras FIO')

<x-app-layout>
    <x-slot name="header">
        <div class="container content-with-footer-buffer">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h4 class="fw-bold mb-1">Gestión de Carreras</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item text-muted">Gestión Académica</li>
                            <li class="breadcrumb-item"><a href="{{ route('careers.index') }}">Carreras</a></li>
                            <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">{{ $career->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-3">
                    <a href="{{ route('careers.index') }}" class="btn btn-outline-primary me-2">← Volver</a>
                    <a href="{{ route('careers.edit', $career) }}" class="btn btn-primary">Editar</a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="container content-with-footer-buffer">
        @include('partials.alerts')

        <div class="card shadow">
            <div class="card-header bg-white">
                <h3 class="card-title mb-0">Detalles de la carrera</h3>
            </div>

            <div class="card-body row">
                <div class="col-md-6 mb-3">
                    <strong>Carrera:</strong> {{ $career->name }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Departamento:</strong> {{ $career->department->name ?? 'N/A' }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Coordinador:</strong>
                    {{ optional($career->teacher)->lastname && optional($career->teacher)->name
                        ? $career->teacher->lastname . ' ' . $career->teacher->name
                        : 'N/A' }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
