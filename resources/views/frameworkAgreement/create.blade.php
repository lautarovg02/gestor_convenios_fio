@extends('layouts.app')

@vite('resources\css\form_convenios\formCreateAgreement.css')

@section('content')
<div class="container mt-4"> <!-- ancho máximo fijo -->
    <h2 class="mb-3 text-center">CREAR CONVENIO MARCO</h2>
    <p class="textCampos mb-1 text-center"><span class="text-danger">*</span> Campos obligatorios</p>

    <form action="{{ route('frameworkAgreement.store') }}" method="POST" enctype="multipart/form-data">
        @csrf


        {{-- Contraparte --}}
        <div class=" mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Datos de contraparte</h4>
            <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccionar Empresa</label>
            <select id="selectCompany" name="company_id" class="form-select">
                <option value="">Seleccione una empresa</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id  }}">
                    {{ $company->denomination }} - {{ $company->cuit }}
                </option>
                @endforeach
            </select>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Razón Social<span class="InputImportant">*</span></label>
                <input type="text" placeholder="Razón Social" name="razon_social" class="form-control" value="{{ old('razon_social') }}">
                @error('razon_social')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label d-block fs-6 fw-bold">Ámbito<span class="InputImportant">*</span></label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ambito" value="nacional" id="ambitoNacional"
                        {{ old('ambito') == 'nacional' ? 'checked' : '' }}>
                    <label class="form-check-label" for="ambitoNacional">Nacional</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ambito" value="internacional" id="ambitoInternacional"
                        {{ old('ambito') == 'internacional' ? 'checked' : '' }}>
                    <label class="form-check-label" for="ambitoInternacional">Internacional</label>
                </div>
                @error('ambito')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">CUIT<span class="InputImportant">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" name="cuit_prefijo" placeholder="23" maxlength="2"
                        pattern="\d{2}" value="{{ old('cuit_prefijo') }}">
                    <span class="input-group-text">-</span>
                    <input type="text" class="form-control" name="cuit_dni" placeholder="12345678" maxlength="8"
                        pattern="\d{7,8}" value="{{ old('cuit_dni') }}">
                    <span class="input-group-text">-</span>
                    <input type="text" class="form-control" name="cuit_dv" placeholder="9" maxlength="1"
                        pattern="\d{1}" value="{{ old('cuit_dv') }}">
                </div>
                <div class="form-text">Formato: XX-XXXXXXXX-X</div>
                @error('contact_cuil')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Rubro</label>
                <input type="text" placeholder="Rubro de la empresa" name="rubro" class="form-control" value="{{ old('rubro') }}">
                @error('rubro')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="entity" class="form-label fs-6">Entidad<span class="InputImportant">*</span></label>
                <select name="entidad" id="entity" class="form-select">
                    <option value="">Seleccionar</option>
                    <option value="privada" {{ old('entidad') == 'privada' ? 'selected' : '' }}>Privada</option>
                    <option value="publica" {{ old('entidad') == 'publica' ? 'selected' : '' }}>Pública</option>
                </select>
                @error('entidad')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Dedicación<span class="InputImportant">*</span></label>
                <input type="text" placeholder="Dedicación de la empresa" name="dedicacion" class="form-control" value="{{ old('dedicacion') }}">
                @error('dedicacion')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Titular / Representante Legal / Apoderado<span class="InputImportant">*</span></label>
                <input type="text" placeholder="Titular / Representante Legal / Apoderado" name="titular" class="form-control" value="{{ old('titular') }}">
                @error('titular')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label d-block fs-6 fw-bold">Cláusula de Confidencialidad<span class="InputImportant">*</span></label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="confidencialidad" value="si" id="confSi"
                        {{ old('confidencialidad') == 'si' ? 'checked' : '' }}>
                    <label class="form-check-label" for="confSi">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="confidencialidad" value="no" id="confNo"
                        {{ old('confidencialidad') == 'no' ? 'checked' : '' }}>
                    <label class="form-check-label" for="confNo">No</label>
                </div>
                @error('confidencialidad')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Dirección --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Dirección de contraparte</h4>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">País<span class="InputImportant">*</span></label>
                <input type="text" name="pais" placeholder="País" class="form-control" value="{{ old('pais') }}">
                @error('pais')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">

                <label class="form-label fs-6 fw-bold">Provincia</label><span class="text-danger"> *</span>
                <select id="provincia" name="provincia" class="form-select">
                    <option value="">Seleccione una provincia</option>
                    @foreach ($provincias as $provincia)
                    <option value="{{ $provincia['nombre'] }}">{{ $provincia['nombre'] }}</option>
                    @endforeach
                </select>

                {{-- Validación de errores --}}

                @error('provincia')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Ciudad</label><span class="text-danger"> *</span>

                <select id="ciudad" name="localidad" class="form-select" disabled value="{{ old('ciudad') }}">
                    <option value="">Seleccione una ciudad</option>
                </select>

                {{-- Validación de errores --}}

                @error('localidad')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Código Postal<span class="InputImportant">*</span></label>
                <input type="number" name="codigo_postal" placeholder="Codigo postal" class="form-control" value="{{ old('codigo_postal') }}">
                @error('codigo_postal')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Calle<span class="InputImportant">*</span></label>
                <input type="text" name="calle" placeholder="Calle" class="form-control" value="{{ old('calle') }}">
                @error('calle')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Numero<span class="InputImportant">*</span></label>
                <input type="text" name="nro_calle" placeholder="Nro. de calle" class="form-control" value="{{ old('nro_calle') }}">
                @error('nro_calle')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


        </div>



        {{-- Representante Contacto --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Representante de contacto</h4>
            {{-- Cada campo en bloque separado y ancho completo --}}
            <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccionar Representante</label>
            <select id="selectEmployeeContact" name="id_employee" class="form-select">
                <option value="">Seleccionar representante de contacto</option>
            </select>

            <div class="containerInputNameLastName">
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Nombre(s)<span class="InputImportant">*</span></label>
                    <input type="text" name="contact_nombre" placeholder="Nombre" class="form-control" required
                        value="{{ old('contact_nombre') }}">
                    @error('contact_nombre')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Apellido(s)<span class="InputImportant">*</span></label>
                    <input type="text" placeholder="Apellido" name="contact_apellido" class="form-control" required
                        value="{{ old('contact_apellido') }}">
                    @error('contact_apellido')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">DNI<span class="InputImportant">*</span></label>
                <input type="number" name="contact_dni" placeholder="DNI" class="form-control"
                    value="{{ old('contact_dni') }}">
                @error('contact_dni')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">CUIL<span class="InputImportant">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" name="cuil_prefijo" placeholder="20" maxlength="2"
                        pattern="\d{2}" required value="{{ old('cuil_prefijo') }}">
                    <span class="input-group-text">-</span>
                    <input type="text" class="form-control" name="cuil_dni" placeholder="12345678" maxlength="8"
                        pattern="\d{7,8}" required value="{{ old('cuil_dni') }}">
                    <span class="input-group-text">-</span>
                    <input type="text" class="form-control" name="cuil_dv" placeholder="3" maxlength="1"
                        pattern="\d{1}" required value="{{ old('cuil_dv') }}">
                    @error('contact_cuil')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text">Formato: XX-XXXXXXXX-X</div>
            </div>


            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Celular<span class="InputImportant">*</span></label>
                <input type="number" placeholder="Celular" name="contact_celular" class="form-control" required
                    value="{{ old('contact_celular') }}">
                @error('contact_celular')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Email<span class="InputImportant">*</span></label>
                <input type="email" placeholder="Email" name="contact_email" class="form-control" required
                    value="{{ old('contact_email') }}">
                @error('contact_email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Empresa<span class="InputImportant">*</span></label>
                <input type="text" placeholder="Empresa" name="contact_empresa" class="form-control" required
                    value="{{ old('contact_empresa') }}">
                @error('contact_empresa')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Cargo<span class="InputImportant">*</span></label>
                <input type="text" placeholder="Cargo en empresa" name="contact_cargo" class="form-control"
                    value="{{ old('contact_cargo') }}">
                @error('contact_cargo')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>


        {{-- Representante Firma --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Representante de firma</h4>

            <div class="containerCheckSameRepresentative">
                <input type="checkbox" id="sameRepresentative"> El representante de contacto es también el de firma
            </div>


            <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccionar Representante</label>
            <select id="selectEmployeeContact" name="id_employee" class="form-select">
                <option value="">Seleccionar representante de contacto</option>
            </select>
            <div class="containerInputNameLastName">

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Nombre(s)<span class="InputImportant">*</span></label>
                    <input type="text" name="firma_nombre" placeholder="Nombre" class="form-control"
                        value="{{ old('firma_nombre') }}">
                    @error('firma_nombre')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Apellido(s)<span class="InputImportant">*</span></label>
                    <input type="text" name="firma_apellido" placeholder="Apellido" class="form-control"
                        value="{{ old('firma_apellido') }}">
                    @error('firma_apellido')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">DNI<span class="InputImportant">*</span></label>
                <input type="number" name="firma_dni" placeholder="DNI" class="form-control"
                    value="{{ old('firma_dni') }}">
                @error('firma_dni')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Razon Social de Empresa<span class="InputImportant">*</span></label>
                <input type="text" placeholder="Razon social" name="firma_empresa_razon_social" class="form-control"
                    value="{{ old('firma_empresa_razon_social') }}">
                @error('firma_empresa_razon_social')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Cargo<span class="InputImportant">*</span></label>
                <input type="text" name="firma_cargo" placeholder="Cargo en empresa" class="form-control"
                    value="{{ old('firma_cargo') }}">
                @error('firma_cargo')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Email<span class="InputImportant">*</span></label>
                <input type="email" name="firma_email" placeholder="Email" class="form-control"
                    value="{{ old('firma_email') }}">
                @error('firma_email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>


        {{-- Lugar y Fecha --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Lugar y fecha de firma</h4>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Lugar<span class="InputImportant">*</span></label>
                <input type="text" name="lugar_firma" placeholder="Lugar" class="form-control" value="{{ old('lugar_firma') }}">
                @error('lugar_firma')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Fecha<span class="InputImportant">*</span></label>
                <input type="date" name="fecha_firma" class="form-control" value="{{ old('fecha_firma') }}">
                @error('fecha_firma')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Documentos --}}
        <div class="mb-4 border rounded containerSectionForm">
            <h4 class="TitleSection">Documentos a adjuntar</h4>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Constancia de AFIP</label>
                <input type="file" name="doc_afip" accept=".pdf,image/*" placeholder="Constancia de AFIP" class="form-control">
                @error('doc_afip')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Estatuto de confirmación</label>
                <input type="file" name="doc_estatuto" accept=".pdf,image/*" placeholder="Estatuto de confirmación" class="form-control">
                @error('doc_estatuto')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fs-6 fw-bold">Asignación de autoridades</label>
                <input type="file" name="doc_autoridades" accept=".pdf,image/*" placeholder="Asignación de autoridades" class="form-control">
                @error('doc_autoridades')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between containerButtons">
            <a href="{{ url()->previous() }}" class="btForm btn btn-secondary">Volver</a>
            <button type="submit" class="btForm btn btn-success">Enviar solicitud</button>
        </div>
    </form>

</div>
@endsection
@section('scripts')
@vite('resources/js/formAgreements/frameworkAgreement/getCitiesAndProvinces.js')
@vite('resources/js/formAgreements/frameworkAgreement/getCompany.js')
@vite('resources/js/formAgreements/frameworkAgreement/selectEmployee.js')
@endsection