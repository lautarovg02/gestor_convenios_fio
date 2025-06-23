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

        {{-- Empresa --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Datos de la Empresa</h4>

            {{-- Razón Social --}}
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Razón Social de la Empresa <span class="text-danger">*</span></label>
                <input type="text" name="empresa_razon_social" placeholder="Razón Social" class="form-control" value="{{ old('empresa_razon_social') }}" required>
                @error('empresa_razon_social')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nominación --}}
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Nominación</label>
                <input type="text" name="empresa_nominacion" placeholder="Nombre comercial o institucional" class="form-control" value="{{ old('empresa_nominacion') }}">
                @error('empresa_nominacion')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Domicilio legal (calle y número) --}}
            <div class="containerInputNameLastName">
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Calle</label>
                    <input type="text" name="empresa_calle" placeholder="Calle" class="form-control" value="{{ old('empresa_calle') }}">
                    @error('empresa_calle')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Número</label>
                    <input type="text" name="empresa_numero" placeholder="Nro" class="form-control" value="{{ old('empresa_numero') }}">
                    @error('empresa_numero')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Ciudad --}}
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Ciudad</label>
                <input type="text" name="empresa_ciudad" placeholder="Ciudad" class="form-control" value="{{ old('empresa_ciudad') }}">
                @error('empresa_ciudad')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Provincia --}}
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Provincia</label>
                <input type="text" name="empresa_provincia" placeholder="Provincia" class="form-control" value="{{ old('empresa_provincia') }}">
                @error('empresa_provincia')
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
                <input type="text" name="pais" class="form-control"  placeholder="País" value="{{ old('pais') }}" required>
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




        {{-- OBJETIVOS --}}
        <div class="mb-4 border rounded containerSectionForm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="TitleSection">Objetivo</h4>

            </div>
            <div class="input-group mb-3">
                <input type="text" id="nuevoObjetivoInput" class="form-control" placeholder="Ingrese un objetivo">
            </div>


            <div class="input-group mb-3">
                <input type="text" id="nuevaTareaInput" class="form-control" placeholder="Ingrese un Tarea">
                <button type="button" class="btn btn-success" id="addTareaBtn">+</button>
            </div>

            <div id="tareasList"></div>
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