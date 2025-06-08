@extends('layouts.app')

@section('content')
<div class="container mt-4" style="max-width: 1000px;"> <!-- ancho máximo fijo -->
    <h2 class="mb-3 text-center">CREAR CONVENIO MARCO</h2>
    <p><span class="text-danger">*</span> Campos obligatorios</p>

    <form action="{{ route('frameworkAgreement.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Representante Contacto --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <h4>Representante Contacto</h4>
            {{-- Cada campo en bloque separado y ancho completo --}}
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">* Nombre:</label>
                <input type="text" name="contact_nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">* Apellido:</label>
                <input type="text" name="contact_apellido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">* Celular:</label>
                <input type="number" name="contact_celular" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">* Mail:</label>
                <input type="email" name="contact_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">* Empresa:</label>
                <input type="text" name="contact_empresa" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Cargo:</label>
                <input type="text" name="contact_cargo" class="form-control">
            </div>
        </div>

        {{-- Contraparte --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <h4>Contraparte</h4>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Razón Social:</label>
                <input type="text" name="razon_social" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label d-block fs-6 fw-bold">Ámbito:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ambito" value="nacional" id="ambitoNacional">
                    <label class="form-check-label" for="ambitoNacional">Nacional</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ambito" value="internacional" id="ambitoInternacional">
                    <label class="form-check-label" for="ambitoInternacional">Internacional</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">CUIT:</label>
                <input type="number" name="cuit" class="form-control">
                 <small class="form-hint">Ingresar <b>CUIT</b> sin puntos.</small>
                            @error('cuit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Rubro:</label>
                <input type="text" name="rubro" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Entidad:</label>
                <input type="text" name="entidad" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Dedicación:</label>
                <input type="text" name="dedicacion" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Titular / Representante Legal / Apoderado:</label>
                <input type="text" name="titular" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label d-block fs-6 fw-bold">Cláusula de Confidencialidad:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="confidencialidad" value="si" id="confSi">
                    <label class="form-check-label" for="confSi">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="confidencialidad" value="no" id="confNo">
                    <label class="form-check-label" for="confNo">No</label>
                </div>
            </div>
        </div>

        {{-- Dirección --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <h4>Dirección</h4>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Domicilio Legal:</label>
                <input type="text" name="domicilio" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Código Postal:</label>
                <input type="number" name="codigo_postal" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Localidad:</label>
                <input type="text" name="localidad" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Provincia:</label>
                <input type="text" name="provincia" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">País:</label>
                <input type="text" name="pais" class="form-control">
            </div>
        </div>

        {{-- Representante Firma --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <h4>Representante Firma</h4>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Nombre:</label>
                <input type="text" name="firma_nombre" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Apellido:</label>
                <input type="text" name="firma_apellido" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">DNI:</label>
                <input type="number" name="firma_dni" class="form-control">
                 <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Cargo:</label>
                <input type="text" name="firma_cargo" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Mail:</label>
                <input type="email" name="firma_mail" class="form-control">
            </div>
        </div>

        {{-- Lugar y Fecha --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <h4>Lugar y Fecha de Firma</h4>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Lugar:</label>
                <input type="text" name="lugar_firma" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Fecha:</label>
                <input type="date" name="fecha_firma" class="form-control">
            </div>
        </div>

        {{-- Documentos --}}
        <div class="mb-4 p-3 border rounded bg-light">
            <h4>Documentos a adjuntar</h4>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Constancia AFIP:</label>
                <input type="file" name="doc_afip" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Estatuto de confirmación:</label>
                <input type="file" name="doc_estatuto" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Asignación de autoridades:</label>
                <input type="file" name="doc_autoridades" class="form-control">
            </div>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
            <button type="submit" class="btn btn-success">Enviar solicitud </button>
        </div>
    </form>
</div>
@endsection
