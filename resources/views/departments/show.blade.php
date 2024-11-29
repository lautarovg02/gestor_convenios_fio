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
                        <a href="{{ route('departments.index') }}" class="btn btn-primary d-none d-sm-inline-block">
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
                            <h3 class="card-title">Detalles del departamento: {{ $department->name }} </h3>
                        </div>
                            <div class="card-body mb-3">

                                <div class="form-group mb-5">
                                    <h4><strong>Director del departamento:</strong></h4>
                                    <h4 class="ms-5">{{ $department->teacher->lastname }}{{ $department->teacher->name }}</h4>
                                </div>
                                <div class="form-group mb-4">
                                    <h4><strong>Carreras pertenecen al departamento:</strong></h4>
                                </div>
                                <div>
                                    @if ($careersBelongsToDepartment->isEmpty())
                                        <p>No hay carreras asociadas a este departamento.</p>
                                    @else
                                        <ul>
                                            @foreach ($careersBelongsToDepartment as $career)
                                                <li class="mb-3 mt-5"><h5> <strong> {{$career->name}} , coordinador: </strong> {{ $career->teacher->lastname }}      {{ $career->teacher->name }}</h5>
                                                    <h5>Docentes a cargo:</h5> </li>
                                                    @foreach ($career->teachers as $teacher)
                                                    <li class="ms-5 ps-5 list-group-item"> <h6> <strong> {{ $teacher->lastname }}      {{ $teacher->name }}</strong></h6></li>
                                                    @endforeach
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

