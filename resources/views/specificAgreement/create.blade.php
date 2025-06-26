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

    <!--
        ORDEN
        1.-EMPRESA
        2.-REPRESENTANTE DE CONTACTO
        3.-REPRESENTANTE DE FIRMA


        PREGUNTAS:
        - CUIT de la empresa

    -->

    <p class="textCampos"><span class="text-danger">*</span> Campos obligatorios</p>

    <form id="formulario" action="{{ route('specificAgreement.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Select convenio marco --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Seleccionar Convenio Marco</h4>
            <select class="form-select" id="convenioMarcoSelect">
                <option value="">Seleccionar convenio marco...</option>
                @foreach ($conveniosMarco as $convenio)
                <option value="{{ $convenio->id }}">
                    {{ $convenio->company->denomination }}
                </option>
                @endforeach
            </select>
        </div>



        {{-- Contraparte --}}
        <div class=" mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Contraparte</h4>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Razón Social</label>
                <input type="text" placeholder="Razón Social" name="razon_social" class="form-control" value="{{ old('razon_social') }}">

                {{-- Validación de errores --}}

                @error('razon_social')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label d-block fs-6 fw-bold">Ámbito</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ambito" value="nacional"
                        id="ambitoNacional" checked>
                    <label class="form-check-label" for="ambitoNacional">Nacional</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ambito" value="internacional"
                        id="ambitoInternacional">
                    <label class="form-check-label" for="ambitoInternacional">Internacional</label>
                </div>
                @error('ambito')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">CUIT</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="contraparte_cuit_prefijo" placeholder="23" maxlength="2"
                        pattern="\d{2}" value="{{ old('contraparte_cuit_prefijo') }}">
                    <span class="input-group-text">-</span>
                    <input type="number" class="form-control" name="contraparte_cuit_dni" placeholder="12345678"
                        maxlength="8" pattern="\d{7,8}" value="{{ old('contraparte_cuit_dni') }}">
                    <span class="input-group-text">-</span>
                    <input type="number" class="form-control" name="contraparte_cuit_dv" placeholder="9" maxlength="1"
                        pattern="\d{1}" value="{{ old('contraparte_cuit_dv') }}">
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
                <label class="form-label fs-6 fw-bold">Rubro</label>
                <input type="text" placeholder="Rubro de la empresa" name="contraparte_rubro" class="form-control" value="{{ old('contraparte_rubro') }}">

                {{-- Validación de errores --}}

                @error('contraparte_rubro')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Titular / Representante Legal / Apoderado</label>
                <input type="text" placeholder="Titular / Representante Legal / Apoderado" name="titular"
                    class="form-control" value="{{ old('titular') }}">
                @error('titular')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">
                <label class="form-label d-block fs-6 fw-bold">Cláusula de Confidencialidad</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="confidencialidad" value="si"
                        id="confSi" checked>
                    <label class="form-check-label" for="confSi">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="confidencialidad" value="no"
                        id="confNo">
                    <label class="form-check-label" for="confNo">No</label>
                </div>

                @error('confidencialidad')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>
        </div>



        {{-- Direccion --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection d-flex align-items-center">
                Dirección
            </h4>

            {{-- Domicilio Legal (Calle y Número) --}}
            <div class="containerInputNameLastName">
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Calle</label>
                    <input type="text" name="domicilio_legal_calle" class="form-control" placeholder="Calle" value="{{ old('domicilio_legal_calle') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Número</label>
                    <input type="text" name="domicilio_legal_numero" class="form-control" placeholder="Número" value="{{ old('domicilio_legal_numero') }}">
                </div>
            </div>

            {{-- Código Postal --}}
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Código postal</label>
                <input type="text" name="codigo_postal" class="form-control" placeholder="Código Postal" value="{{ old('codigo_postal') }}">
            </div>

            {{-- Ciudad --}}
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Ciudad</label>
                <input type="text" name="localidad" class="form-control" placeholder="Ciudad" value="{{ old('localidad') }}" required>
            </div>

            {{-- Provincia --}}
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Provincia</label>
                <input type="text" name="provincia" class="form-control" placeholder="Provincia" value="{{ old('provincia') }}" required>
            </div>

            {{-- País --}}
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">País</label>
                <input type="text" name="pais" class="form-control" placeholder="País" value="{{ old('pais') }}" required>
            </div>
        </div>





        {{-- Representante Contacto --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Representante de contacto</h4>
            <div class="containerInputNameLastName">
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Nombre(s)</label>
                    <input id="contact_nombre" type="text" name="contact_nombre" placeholder="Nombre" class="form-control" value="{{ old('contact_nombre') }}" required>

                    @error('contact_nombre')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Apellido(s)</label>
                    <input id="contact_apellido" type="text" placeholder="Apellido" name="contact_apellido" class="form-control" value="{{ old('contact_apellido') }}" required>

                    @error('contact_apellido')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Cargo</label>
                <input id="contact_cargo" type="text" placeholder="Cargo en empresa" name="contact_cargo" class="form-control" value="{{ old('contact_cargo') }}" required">

                @error('contact_cargo')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Celular</label>
                <input type="number" placeholder="Celular" name="contact_celular" class="form-control" value="{{ old('contact_celular') }}" required>

                @error('contact_celular')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Email</label>
                <input id="contact_email" type="email" placeholder="Email" name="contact_email" class="form-control" value="{{ old('contact_email') }}" required>

                @error('contact_email')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>
            <!--                    NO LO NOMBRAA - PREGUNTAR!
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Empresa</label>
                <input id="contact_empresa" type="text" placeholder="Empresa" name="contact_empresa" class="form-control" value="{{ old('contact_empresa') }}" required>

                @error('contact_empresa')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>
            -->
        </div>


        {{-- Representante Firma --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Representante de firma</h4>

            <div class="containerCheckSameRepresentative">
                <input type="checkbox" id="sameRepresentative"> El representante de contacto es también el de firma
            </div>

            <div class="containerInputNameLastName">

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Nombre(s)</label>
                    <input id="firma_nombre" type="text" name="firma_nombre" placeholder="Nombre" class="form-control" value="{{ old('firma_nombre') }}">

                    @error('firma_nombre')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Apellido(s)</label>
                    <input id="firma_apellido" type="text" name="firma_apellido" placeholder="Apellido" class="form-control" value="{{ old('firma_apellido') }}">

                    {{-- Validación de errores --}}

                    @error('firma_apellido')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">DNI</label>
                <input id="firma_dni" type="number" name="firma_dni" placeholder="DNI" class="form-control" value="{{ old('firma_dni') }}">
                <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>

                @error('firma_dni')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Email</label>
                <input id="firma_email" type="email" name="firma_email" placeholder="Email" class="form-control" value="{{ old('firma_email') }}">

                {{-- Validación de errores --}}

                @error('firma_email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Cargo</label>
                <input id="firma_cargo" type="text" name="firma_cargo" placeholder="Cargo en empresa" class="form-control" value="{{ old('firma_cargo') }}">

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
                <label class="form-label fs-6 fw-bold">Lugar</label>
                <input type="text" name="lugar_firma" placeholder="Lugar" class="form-control" value="{{ old('lugar_firma') }}">

                {{-- Validación de errores --}}

                @error('lugar_firma')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Fecha</label>
                <input type="date" name="fecha_firma" class="form-control" value="{{ old('fecha_firma') }}">

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
                <label class="form-label fs-6 fw-bold">Compromisos acordados</label>
                <textarea name="compromisos" class="form-control" rows="4" placeholder="Describa los compromisos de cada parte">{{ old('compromisos') }}</textarea>
                @error('compromisos')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Responsable de control y comunicación --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Responsable de Control y Comunicación</h4>

            <div class="containerInputNameLastName">
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Nombre</label>
                    <input type="text" name="responsable_nombre" class="form-control" value="{{ old('responsable_nombre') }}" placeholder="Nombre completo">
                    @error('responsable_nombre')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Apellido</label>
                    <input type="text" name="responsable_apellido" class="form-control" value="{{ old('responsable_apellido') }}" placeholder="Apellido">
                    @error('responsable_apellido')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Cargo</label>
                <input type="text" name="responsable_cargo" class="form-control" value="{{ old('responsable_cargo') }}" placeholder="Cargo en la empresa o institución">
                @error('responsable_cargo')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Email</label>
                <input type="email" name="responsable_email" class="form-control" value="{{ old('responsable_email') }}" placeholder="Correo electrónico">
                @error('responsable_email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- OBJETIVOS --}}
        <div class="mb-4 border rounded containerSectionForm">

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Objetivos</label>
                <textarea name="objetivo" class="form-control" rows="4" placeholder="Describa el objetivo ">{{ old('objetivo') }}</textarea>
                @error('objetivos')
                <div class="text-danger">{{ $message }}</div>
                @enderror
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

    </form>

</div>
@endsection


@section('scripts')
@vite('resources/js/formAgreements/createAgreement/formCreateAgreement.js')
@endsection