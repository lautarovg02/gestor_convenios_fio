<!-- resources/views/companies/show.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">

                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto mt-3 mb-3 d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('careers.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body mb-5">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="row justify-content-center">
                    <div class="card col-8">
                        <div class="card-header">
                            <h3 class="card-title">Detalles de la Carrera: {{ $career->name }} </h3>
                        </div>
                            <div class="card-body mb-3">

                                <div class="form-group mb-3">
                                    <h5><strong>Coordinador:</strong></h5>
                                    <h5 class="ms-5">{{ $career->teacher->lastname }}{{ $career->teacher->name }}</h5>
                                </div>
                                <div class="form-group mb-3">
                                    <h5><strong>Departamento al cual pertenece:</strong></h5>
                                    <h5 class="ms-5">{{ $career->department->name}}</h5>
                                </div>
                                <div class="form-group mb-3">
                                    <h5><strong>Nombre del director de departamento::</strong></h5>
                                    <h5 class="ms-5">{{ $career->department->teacher->lastname }} {{ $career->department->teacher->lastname }}</h5>
                                </div>
                                <div>
                                    <h5>Docentes de la carrera:</h5>
                                    @if ($teachersBelongsToCareer->isEmpty())
                                        <p>No hay docentes asociados a esta carrera.</p>
                                    @else
                                        <ul>
                                            @foreach ($teachersBelongsToCareer as $teacher)
                                                <li> <strong>{{ $teacher->lastname }}      {{ $teacher->name }}</strong> -  DNI: {{ $teacher->dni }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div >
@endsection

