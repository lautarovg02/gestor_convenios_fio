@extends('layouts.app')

@vite('resources\css\form_convenios\formCreateAgreement.css')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3 text-center">CREAR CONVENIO ESPECIFICO</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <p class="textCampos"><span class="text-danger">*</span> Campos obligatorios</p>

        <form id="formulario" action="{{ route('specificAgreement.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Select convenio marco --}}
            <div class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Seleccionar Empresa <span class="text-danger">*</span></h4>
                <select id="convenioMarcoSelect" name="contract_id" class="form-select" required>
                    <option value="">Seleccionar</option>
                    @foreach ($companies as $company)
                        @foreach ($company->contracts as $contract)
                            @if ($contract->typeFrameworkAgreement)
                                <option value="{{ $contract->id }}">
                                    {{ $company->denomination }} - (CUIT: {{ $company->cuit }})
                                </option>
                            @endif
                        @endforeach
                    @endforeach
                </select>


            </div>

            <div class="d-flex flex-wrap justify-content-center">

                {{-- Contraparte --}}
                <div class=" mb-4 border rounded containerSectionForm">
                    <h4 class="TitleSection">Contraparte</h4>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Razón Social</label><span class="text-danger"> *</span>

                        <input type="text" placeholder="Razón Social" id="razon_social" name="razon_social"
                            class="form-control" value="{{ old('razon_social') }}" readonly>

                        {{-- Validación de errores --}}

                        @error('razon_social')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block fs-6 fw-bold">Ámbito <span class="text-danger">
                            *</span></label></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ambito" value="nacional"
                                id="ambitoNacional" checked readonly>
                            <label class="form-check-label" for="ambitoNacional">Nacional</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ambito" value="internacional"
                                id="ambitoInternacional" readonly>
                            <label class="form-check-label" for="ambitoInternacional">Internacional</label>
                        </div>
                        @error('ambito')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">CUIT</label> <span class="text-danger"> *</span>
                        <div class="input-group">
                            <input type="number" class="form-control" id="contraparte_cuit_prefijo"
                                name="contraparte_cuit_prefijo" placeholder="23" maxlength="2" pattern="\d{2}"
                                value="{{ old('contraparte_cuit_prefijo') }}" readonly>
                            <span class="input-group-text">-</span>
                            <input type="number" class="form-control" id="contraparte_cuit_dni" name="contraparte_cuit_dni"
                                placeholder="12345678" maxlength="8" pattern="\d{7,8}"
                                value="{{ old('contraparte_cuit_dni') }}" readonly>
                            <span class="input-group-text">-</span>
                            <input type="number" class="form-control" id="contraparte_cuit_dv" name="contraparte_cuit_dv"
                                placeholder="9" maxlength="1" pattern="\d{1}" value="{{ old('contraparte_cuit_dv') }}"
                                readonly>
                        </div>
                        <div class="form-text">Formato: XX-XXXXXXXX-X</div>

                        @error('contraparte_cuit_prefijo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        @error('contraparte_cuit_dni')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        @error('contraparte_cuit_dv')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        @error('contraparte_cuit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Rubro</label><span class="text-danger"> *</span>
                        <input type="text" placeholder="Rubro de la empresa" id="contraparte_rubro"
                            name="contraparte_rubro" class="form-control" value="{{ old('contraparte_rubro') }}" readonly>

                        {{-- Validación de errores --}}

                        @error('contraparte_rubro')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Titular / Representante Legal / Apoderado</label><span
                            class="text-danger"> *</span>
                        <input type="text" id="titular" placeholder="Representante de la contraparte" name="titular"
                            class="form-control" value="{{ old('titular') }}" required>
                        @error('titular')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block fs-6 fw-bold">Cláusula de Confidencialidad</label><span
                            class="text-danger"> *</span>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="confidencialidad" value="si"
                                id="confSi" checked>
                            <label class="form-check-label" for="confSi">Sí</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="confidencialidad" value="no" id="confNo">
                            <label class="form-check-label" for="confNo">No</label>
                        </div>

                        @error('confidencialidad')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                </div>



                {{-- Direccion --}}
                <div class="mb-4 border rounded containerSectionForm">
                    <h4 class="TitleSection d-flex align-items-center">Dirección</h4>


                    {{-- País --}}
                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">País</label><span class="text-danger"> *</span>
                        <input type="text" id="pais" name="pais" class="form-control" placeholder="País"
                            value="{{ old('pais') }}" required readonly>

                    </div>

                    {{-- Provincia --}}
                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Provincia</label><span class="text-danger"> *</span>
                        <input type="text" id="provincia" name="provincia" class="form-control"
                            placeholder="Provincia" value="{{ old('provincia') }}" required readonly>
                    </div>


                    {{-- Ciudad --}}
                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Ciudad</label><span class="text-danger"> *</span>
                        <input type="text" id="localidad" name="empresa_ciudad" class="form-control"
                            placeholder="Ciudad" value="{{ old('empresa_ciudad') }}" required readonly>
                    </div>

                    {{-- Código Postal --}}
                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Código postal</label><span class="text-danger">
                            *</span>
                        <input type="text" id="codigo_postal" name="codigo_postal" class="form-control"
                            placeholder="Código Postal" value="{{ old('codigo_postal') }}" required>
                    </div>
                    <div class="containerInputNameLastName">
                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Calle</label><span class="text-danger"> *</span>
                            <input type="text" id="domicilio_legal_calle" name="empresa_calle" class="form-control"
                                placeholder="Calle" value="{{ old('empresa_calle') ?? ($empresa_calle ?? '') }}"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Número</label><span class="text-danger"> *</span>
                            <input type="text" id="domicilio_legal_numero" name="empresa_numero" class="form-control"
                                placeholder="Número" value="{{ old('empresa_numero') }}" readonly>
                        </div>
                    </div>

                </div>





                {{-- Representante Contacto --}}
                <div class="mb-4 border rounded containerSectionForm">
                    <h4 class="TitleSection">Representante de contacto</h4>
                    <div class="containerInputNameLastName">
                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Nombre(s)</label><span class="text-danger"> *</span>
                            <input readonly id="contact_nombre" type="text" name="contact_nombre"
                                placeholder="Nombre" class="form-control" value="{{ old('contact_nombre') }}" required>

                            @error('contact_nombre')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Apellido(s)</label><span class="text-danger"> *</span>
                            <input readonly id="contact_apellido" type="text" placeholder="Apellido"
                                name="contact_apellido" class="form-control" value="{{ old('contact_apellido') }}"
                                required>

                            @error('contact_apellido')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Cargo</label><span class="text-danger"> *</span>
                        <input readonly id="contact_cargo" type="text" placeholder="Cargo en empresa"
                            name="contact_cargo" class="form-control" value="{{ old('contact_cargo') }}" required">

                        @error('contact_cargo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Celular</label><span class="text-danger"> *</span>
                        <input readonly id="contact_celular" type="text" placeholder="Celular" name="contact_celular"
                            class="form-control" value="{{ old('contact_celular') }}" required>

                        @error('contact_celular')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Email</label><span class="text-danger"> *</span>
                        <input readonly id="contEmail" type="text" placeholder="Email" name="contact_email"
                            class="form-control" value="{{ old('contact_email') }}" required>

                        @error('contact_email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                {{-- Representante Firma --}}
                <div class="mb-4 border rounded containerSectionForm">
                    <h4 class="TitleSection">Representante de firma</h4>



                    <div class="containerInputNameLastName">

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Nombre(s)</label><span class="text-danger"> *</span>
                            <input readonly id="firma_nombre" type="text" name="firma_nombre" placeholder="Nombre"
                                class="form-control" value="{{ old('firma_nombre') }}">

                            @error('firma_nombre')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Apellido(s)</label><span class="text-danger"> *</span>
                            <input readonly id="firma_apellido" type="text" name="firma_apellido"
                                placeholder="Apellido" class="form-control" value="{{ old('firma_apellido') }}">

                            {{-- Validación de errores --}}

                            @error('firma_apellido')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">DNI</label><span class="text-danger"> *</span>
                        <input readonly id="firma_dni" type="number" name="firma_dni" placeholder="DNI"
                            class="form-control" value="{{ old('firma_dni') }}">
                        <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>

                        @error('firma_dni')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Email</label><span class="text-danger"> *</span>
                        <input readonly id="firma_email" type="email" name="firma_email" placeholder="Email"
                            class="form-control" value="{{ old('firma_email') }}">

                        {{-- Validación de errores --}}

                        @error('firma_email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Cargo</label><span class="text-danger"> *</span>
                        <input readonly id="firma_cargo" type="text" name="firma_cargo"
                            placeholder="Cargo en empresa" class="form-control" value="{{ old('firma_cargo') }}">

                        {{-- Validación de errores --}}

                        @error('firma_cargo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>



                </div>






                {{-- Lugar y Fecha --}}
                <div class="mb-4 border rounded containerSectionForm">
                    <h4 class="TitleSection">Lugar y fecha de firma</h4>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Lugar</label><span class="text-danger"> *</span>
                        <input type="text" id="lugar_firma" name="lugar_firma" placeholder="Lugar"
                            class="form-control" value="{{ old('lugar_firma') }}">

                        {{-- Validación de errores --}}

                        @error('lugar_firma')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Fecha</label><span class="text-danger"> *</span>
                        <input type="date" id="fecha_firma" name="fecha_firma" class="form-control"
                            value="{{ old('fecha_firma') }}">

                        {{-- Validación de errores --}}

                        @error('fecha_firma')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                {{-- Compromiso por las partes --}}
                <div class="mb-4 border rounded containerSectionForm">
                    <h4 class="TitleSection">Compromisos de las PARTES</h4>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Compromisos acordados</label><span class="text-danger">
                            *</span>
                        <textarea name="compromisos" class="form-control" rows="4"
                            placeholder="Describa los compromisos de cada parte">{{ old('compromisos') }}</textarea>
                        @error('compromisos')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Becario --}}
                <div class="mb-4 border rounded containerSectionForm">
                    <h4 class="TitleSection">Seleccionar estudiante <span class="text-danger"> *</span></h4>
                    <select name="student_id" id="student_id" class="form-select mb-3   " required>
                        <option value="">Seleccione un estudiante</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" data-nombre="{{ $student->name }}"
                                data-apellido="{{ $student->last_name }}" data-dni="{{ $student->dni }}">
                                {{ $student->name }} {{ $student->last_name }} - (DNI: {{ $student->dni }})
                            </option>
                        @endforeach
                    </select>

                    <div class="containerInputNameLastName">
                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold" for="Nombre">Nombre</label><span
                                class="text-danger"> *</span>
                            <input type="text" id="becario_nombre" name="becario_nombre" class="form-control"
                                placeholder="Nombre del estudiante" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold" for="Apellido">Apellido</label><span
                                class="text-danger"> *</span>
                            <input type="text" id="becario_apellido" value="" name="becario_apellido"
                                class="form-control" placeholder="Apellido del estudiante" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold" for="DNI">DNI</label><span class="text-danger">
                            *</span>
                        <input type="text" id="becario_dni" name="becario_dni" class="form-control"
                            placeholder="DNI del estudiante" readonly>
                    </div>

                    <!-- fuera del select -->
                    <input type="hidden" name="becario" id="becario" value="">
                </div>



                {{-- Responsable de control y comunicación --}}
                <div class="mb-4 border rounded containerSectionForm">
                    <h4 class="TitleSection">Responsable de Control y Comunicación</h4>
                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold" for="responsable_control_fio">Responsable Control
                            FIO</label><span class="text-danger"> *</span>
                        <input type="text" name="responsable_control_fio" class="form-control"
                            value="{{ old('responsable_control_fio') }}" required>
                               @error('responsable_control_fio')
        <div class="text-danger">{{ $message }}</div>
        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold" for="responsable_control_company">Responsable Control
                            Empresa</label><span class="text-danger"> *</span>
                        <input type="text" name="responsable_control_company" class="form-control"
                            value="{{ old('responsable_control_company') }}" required>
                            @error('responsable_control_company')
        <div class="text-danger">{{ $message }}</div>
        @enderror
                    </div>

                </div>

                {{-- OBJETIVOS --}}
                <div class="mb-4 border rounded containerSectionForm">

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Objetivos</label><span class="text-danger"> *</span>
                        <textarea name="objetivo" class="form-control" rows="4" placeholder="Describa el objetivo del convenio">{{ old('objetivo') }}</textarea>
                        @error('objetivo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 border rounded containerSectionForm">


                    <div class="mb-3">
                        <label for="file" class="form-label fs-6 fw-bold">Adjuntar archivo (opcional)</label>
                        <input class="form-control" type="file" id="file" name="file">
                        @error('file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>

            <div>
                <span class="fw-bold">Una vez enviado el formulario podra descargarlo*</span>
            </div>
            {{-- Botones --}}

            <div class="d-flex justify-content-between containerButtons">
                <a href="{{ url()->previous() }}" class=" btForm btn btn-secondary">Volver</a>
                <button type="submit" class=" btForm btn btn-success">Enviar solicitud</button>
            </div>
            @csrf

        </form>

    </div>
@endsection


@section('scripts')
    @vite('resources/js/formAgreements/createAgreement/formCreateAgreement.js')
@endsection
