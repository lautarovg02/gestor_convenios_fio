<!-- resources/views/companies/edit.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')
    <div class="row row-deck row-cards content-with-footer-buffer">
        <div class="col-12 ">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center ps-4 pe-4">
                    <h3 class="card-title"> Datos de empleado</h3>
                    <nav aria-label="breadcrumb" class="ms-3 mt-3">
                        <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Empleados</a></li>
                            <li class="breadcrumb-item">
                                <span class="text-muted">
                                    {{ $employee->name }} {{ $employee->lastname }}</span>
                                </span>
                            </li>
                            <li class="breadcrumb-item active fw-bold text-decoration-underline" aria-current="page">Editar
                                datos
                            </li>
                        </ol>
                    </nav>
                    <a href="{{ route('companies.employees.index', ['company' => $employee->company_id]) }}"
                        class="btn btn-outline-primary">← Volver</a>
                </div>
                <div>
                    <!-- Mensajes flash de success-->
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <!-- Mensajes flash de erroo-->
                    @if ($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('employees.update', $employee) }}" id="" role="form"
                        enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        @csrf

                        {{-- edit form --}}

                        <!-- NAME -->
                        <div class="form-group mb-3">
                            <label class="form-label required-field fs-6 fw-bold" for='name'> Nombre</label>
                            <div>
                                <input class="form-control" name="name" id="name" type="text"
                                    value="{{ old('denomination', $employee->name) }}" placeholder="Ingrese el nombre "
                                    autocomplete="off">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- LASTNAME -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold fs-6 required-field" for= "lastname">Apellido</label>
                            <div>
                                <input class="form-control" name="lastname" id="lastname" type="text"
                                    value="{{ old('denomination', $employee->lastname) }}" placeholder="Ingrese el apellido "
                                    autocomplete="off">

                            </div>
                            @error('lastname')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- DNI-->
                        <div class="form-group mb-3">
                            <label class="form-label required-field fw-bold fs-6" for="company_name">DNI</label>
                            <input class="form-control " name="dni" id="dni" type="number"
                                value="{{ old('denomination', $employee->dni) }}" placeholder="Ingrese el DNI" autocomplete="off">
                            <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
                            @error('dni')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- POSITION -->
                        <div class="form-group mb-3">
                            <label class="form-label required-field fs-6 fw-bold" for="position">Cargo</label>
                            <input class="form-control" name="position" id="position" type="text"
                                value="{{ old('denomination', $employee->position) }}"
                                placeholder="Ingrese el cargo del empleado" autocomplete="off">
                            @error('position')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- email -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold fs-6" for="email">Email</label>
                            <input class="form-control" name="email" id="email" type="email"
                                value="{{ old('denomination', $employee->email) }}" placeholder="Ingrese el email del empleado"
                                autocomplete="off">
                        </div>

                        <!-- IS REPRESENT -->
                        <div class="form-group mb-3">
                            <label class="form-label required-field fw-bold fs-6 required"
                                for="is_represent">Representante</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_represent" id="is_represent"
                                        value="1"
                                        {{ old('denomination', $employee->is_represent) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_represent">SI</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_represent" id="is_represent"
                                        value="0"
                                        {{ old('denomination', $employee->is_represent) == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_represent">NO</label>
                                </div>
                            </div>
                            @error('is_represent')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- phones --}}
                        <div class="form-group mb-3">
                            <label class="form-label required-field fw-bold fs-6" for="phones">Celular</label>
                            <div>
                                @foreach ($employee->phones as $index => $phone)
                                    <div class="phone-group mb-3">
                                        @if ($loop->count > 1)
                                            <label class="fw-bold">Celular {{ $loop->iteration }}</label>
                                        @endif

                                        <input type="hidden" name="phones[{{ $index }}][id]"
                                            value="{{ $phone->id }}">

                                        <input type="number" name="phones[{{ $index }}][number]"
                                            value="{{ old("phones.$index.number", $phone->number) }}"
                                            class="form-control" placeholder="Ingrese el celular">

                                        @error("phones.$index.number")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                        <input type="hidden" name="phones[{{ $index }}][delete]" value="0"
                                            class="delete-input">

                                        @if ($employee->phones->count() > 1)
                                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-phone"
                                                data-index="{{ $index }}">
                                                Eliminar celular
                                            </button>
                                        @endif
                                    </div>
                                @endforeach


                                <!-- button add phone -->
                                <button id="togglePhoneBtn" type="button" class="mt-3 btn btn-outline-primary btn-sm">
                                    + Agregar nuevo celular
                                </button>

                                <div id="newPhoneField" style="display: none;" class="mt-2">
                                    <input class="form-control" name="phones[new][number]" type="number"
                                        placeholder="Nuevo número celular">

                                </div>
                                @error('phones.new.number')
                                    <div id="error-message" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- button send form-->

                            <div class="mt-5 d-flex justify-content-between flex-row border-top pt-3">
                                <a href="{{ route('companies.employees.index', ['company' => $employee->company_id]) }}"
                                    class="ms-3 btn btn-danger m-2">Cancelar</a>
                                <button type="submit" class="btn btn-success me-3 m-2">
                                    Guardar cambios
                                </button>
                            </div>
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
