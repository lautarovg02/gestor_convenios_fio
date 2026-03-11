@extends('layouts.app')

@vite('resources\css\form_convenios\formCreateAgreement.css')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Revisá estos errores:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($errors->has('general'))
        <div class="alert alert-danger">
            {{ $errors->first('general') }}
        </div>
    @endif


    <div class="container mt-4"> <!-- ancho máximo fijo -->
        <h2 class="mb-3 text-center">CREAR CONVENIO MARCO</h2>
        <p class="textCampos mb-1"><span class="text-danger">*</span> Campos obligatorios</p>

        <form action="{{ route('frameworkAgreement.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4 border rounded containerSectionForm selectCompanySection">
                <h4 class="TitleSection">Seleccionar empresa</h4>
                <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccionar Empresa</label><span
                    class="text-danger"> *</span>
                <select id="selectCompany" name="company_id" class="form-select">
                    <option value="">Seleccione una empresa</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->denomination }} - (CUIT: {{ $company->cuit }})</option>
                    @endforeach
                </select>

                <div class="mt-3 d-flex align-items-center gap-2">
                    <small class="text-muted">¿No encontrás la empresa?</small>
                    <a href="{{ route('companies.create') }}" class="btn btn-sm btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-plus-circle me-1" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                        Crear nueva empresa
                    </a>
                </div>

            </div>

            {{-- Contraparte --}}
            <div class=" mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Datos de contraparte</h4>
                <fieldset id="contreparteFieldset" disabled>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Razón Social</label><span class="text-danger"> *</span>
                    <input type="text" placeholder="Razón Social" name="razon_social" id="razon_social"
                        class="form-control" value="{{ old('razon_social') }}">
                    @error('razon_social')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label d-block fs-6 fw-bold">Ámbito</label><span class="text-danger"> *</span>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="ambito" value="nacional" id="ambitoNacional"
                            {{ old('ambito') == 'nacional' ? 'checked' : '' }}>
                        <label class="form-check-label" for="ambitoNacional">Nacional</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="ambito" value="internacional"
                            id="ambitoInternacional" {{ old('ambito') == 'internacional' ? 'checked' : '' }}>
                        <label class="form-check-label" for="ambitoInternacional">Internacional</label>
                    </div>
                    @error('ambito')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">CUIT</label><span class="text-danger"> *</span>
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
                    <label class="form-label fs-6 fw-bold">Rubro</label><span class="text-danger"> *</span>
                    <input type="text" placeholder="Rubro de la empresa" id="contraparte_rubro" name="contraparte_rubro"
                        class="form-control" value="{{ old('contraparte_rubro') }}">

                    {{-- Validación de errores --}}

                    @error('contraparte_rubro')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>



                <div class="mb-3">
                    <label for="entity" class="form-label fs-6 fw-bold">Entidad</label><span class="text-danger">
                        *</span>
                    <input type="text" placeholder="Entidad de la empresa" name="entidad" id="entidad"
                        class="form-control" value="{{ old('entidad') }}">
                    @error('entidad')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Titular / Representante Legal / Apoderado</label><span
                        class="text-danger"> *</span>

                    <select id="selectEmployeeTitular" name="titular" class="form-select" required>
                        <option value="">Seleccione un titular</option>
                    </select>
                    @error('titular')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label d-block fs-6 fw-bold">Cláusula de Confidencialidad<span class="text-danger">
                            *</span></label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="confidencialidad" value="si"
                            id="confSi" {{ old('confidencialidad') == 'si' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="confSi">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="confidencialidad" value="no"
                            id="confNo" {{ old('confidencialidad') == 'no' ? 'checked' : '' }}>
                        <label class="form-check-label" for="confNo">No</label>
                    </div>
                    @error('confidencialidad')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                </fieldset>
            </div>

            {{-- Dirección --}}
            <div class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Dirección de contraparte</h4>
                <fieldset id="direccionFieldset" disabled>
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">País</label><span class="text-danger"> *</span>
                    <input type="text" name="pais" placeholder="País" class="form-control"
                        value="{{ old('pais') }}">
                    @error('pais')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">

                    <label class="form-label fs-6 fw-bold">Provincia</label><span class="text-danger"> *</span>

                    <input type="text" name="provincia" id="provincia" placeholder="Provincia" class="form-control"
                        value="{{ old('provincia') }}" required>

                    {{-- Validación de errores --}}

                    @error('provincia')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Ciudad</label><span class="text-danger"> *</span>

                    <input type="text" name="localidad" id="ciudad" placeholder="Ciudad" class="form-control"
                        value="{{ old('localidad') }}" required>

                    {{-- Validación de errores --}}

                    @error('localidad')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Código Postal</label><span class="text-danger"> *</span>
                    <input id="postal_code" type="text" name="codigo_postal" placeholder="Codigo postal" class="form-control"
                        value="{{ old('codigo_postal') }}" required>
                    @error('codigo_postal')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Calle</label><span class="text-danger"> *</span>
                    <input type="text" name="calle" placeholder="Calle" class="form-control"
                        value="{{ old('calle') }}">

                    @error('calle')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Numero</label><span class="text-danger"> *</span>
                    <input type="text" name="nro_calle" placeholder="Nro. de calle" class="form-control"
                        value="{{ old('nro_calle') }}">

                    @error('nro_calle')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                </fieldset>
            </div>


            {{-- Representante Contacto --}}
            <div class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Representante de contacto</h4>

                <div id="contactSelectWrapper" class="mb-3 d-none">
                    <label class="form-label fs-6 fw-bold" for="selectEmployeeContact">Seleccionar Representante existente</label><span
                        class="text-danger"> *</span>
                    <select id="selectEmployeeContact" name="id_employee" class="form-select"
                        data-preselected="{{ old('id_employee', $preselectedEmployeeId ?? '') }}">
                        <option value="">Seleccione un representante</option>
                    </select>
                </div>

                <div id="noContactMsg" class="alert alert-info d-none">
                    <i class="bi bi-info-circle me-2"></i>
                    La empresa no tiene representantes registrados. Completá los datos para crear uno nuevo.
                </div>

                <div class="containerInputNameLastName">
                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Nombre(s)</label><span class="text-danger"> *</span>
                        <input type="text" name="contact_nombre" placeholder="Nombre" class="form-control" required
                            value="{{ old('contact_nombre') }}">

                        @error('contact_nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Apellido(s)</label><span class="text-danger"> *</span>
                        <input type="text" placeholder="Apellido" name="contact_apellido" class="form-control"
                            required value="{{ old('contact_apellido') }}">

                        @error('contact_apellido')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">DNI</label><span class="text-danger"> *</span>
                    <input type="number" name="contact_dni" placeholder="DNI" class="form-control"
                        value="{{ old('contact_dni') }}">
                    <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
                    @error('contact_dni')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">CUIL</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="text" class="form-control" name="cuil_prefijo" placeholder="20" maxlength="2"
                            pattern="\d{2}" required value="{{ old('cuil_prefijo') }}">
                        <span class="input-group-text">-</span>
                        <input type="text" class="form-control" name="cuil_dni" placeholder="12345678"
                            maxlength="8" pattern="\d{7,8}" required value="{{ old('cuil_dni') }}">
                        <span class="input-group-text">-</span>
                        <input type="text" class="form-control" name="cuil_dv" placeholder="3" maxlength="1"
                            pattern="\d{1}" required value="{{ old('cuil_dv') }}">
                    </div>
                    @error('contact_cuil')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Formato: XX-XXXXXXXX-X</div>

                </div>


                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Celular</label><span class="text-danger"> *</span>
                    <input type="number" placeholder="Celular" name="contact_celular" class="form-control" required
                        value="{{ old('contact_celular') }}">

                    @error('contact_celular')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Email</label><span class="text-danger"> *</span>
                    <input type="email" placeholder="Email" name="contact_email" class="form-control" required
                        value="{{ old('contact_email') }}">

                    @error('contact_email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Empresa</label><span class="text-danger"> *</span>
                    <input type="text" placeholder="Empresa" name="contact_empresa" class="form-control" required
                        value="{{ old('contact_empresa') }}">

                    @error('contact_empresa')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Cargo</label><span class="text-danger"> *</span>
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

                <div id="firmaSelectWrapper" class="mb-3 d-none mt-2">
                    <label class="form-label fs-6 fw-bold" for="selectEmployeeFirma">Seleccionar Representante de Firma existente</label><span
                        class="text-danger"> *</span>
                    <select id="selectEmployeeFirma" name="id_employee_firma" class="form-select">
                        <option value="">Seleccione un representante de firma</option>
                    </select>
                </div>

                <div id="noFirmaMsg" class="alert alert-info d-none">
                    <i class="bi bi-info-circle me-2"></i>
                    La empresa no tiene representantes de firma registrados. Completá los datos para crear uno nuevo.
                </div>

                <div class="containerInputNameLastName">
                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Nombre(s)</label><span class="text-danger"> *</span>
                        <input type="text" name="firma_nombre" placeholder="Nombre" class="form-control"
                            value="{{ old('firma_nombre') }}">

                        @error('firma_nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Apellido(s)</label><span class="text-danger"> *</span>
                        <input type="text" name="firma_apellido" placeholder="Apellido" class="form-control"
                            value="{{ old('firma_apellido') }}">

                        @error('firma_apellido')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">DNI</label><span class="text-danger"> *</span>
                    <input type="number" name="firma_dni" placeholder="DNI" class="form-control"
                        value="{{ old('firma_dni') }}">
                    <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
                    @error('firma_dni')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror


                </div>
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Razon Social de Empresa</label><span class="text-danger">
                        *</span>
                    <input type="text" placeholder="Razon social" name="firma_empresa_razon_social"
                        class="form-control" value="{{ old('firma_empresa_razon_social') }}">

                    @error('firma_empresa_razon_social')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Cargo</label><span class="text-danger"> *</span>
                    <input type="text" name="firma_cargo" placeholder="Cargo en empresa" class="form-control"
                        value="{{ old('firma_cargo') }}">

                    @error('firma_cargo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">celular</label><span class="text-danger"> *</span>
                    <input type="number" placeholder="Celular" name="firma_celular" class="form-control" required
                        value="{{ old('firma_celular') }}">

                    @error('firma_celular')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Email</label><span class="text-danger"> *</span>
                    <input type="email" name="firma_email" placeholder="Email" class="form-control"
                        value="{{ old('firma_email') }}">

                    @error('firma_email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- SECCIÓN: AUTORIDADES DE LA INSTITUCIÓN --}}
            <div class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Autoridades de la Institución</h4>

                {{-- 1. Selector de Rector --}}
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold" for="rector_id">Rector</label><span class="text-danger"> *</span>
                    <select name="rector_id" id="rector_id" class="form-select">
                        <option value="">Seleccione el Rector</option>
                        @foreach ($rectors as $rector)
                            <option value="{{ $rector->id }}" {{ old('rector_id') == $rector->id ? 'selected' : '' }}>
                                {{ $rector->lastname }} {{ $rector->name }} (DNI: {{ $rector->dni }})
                            </option>
                        @endforeach
                    </select>
                    @error('rector_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 2. Selector de Profesor (o Decano) --}}
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold" for="teacher_id">Profesor / Decano interviniente</label><span class="text-danger"> *</span>
                    <select name="teacher_id" id="teacher_id" class="form-select">
                        <option value="">Seleccione un Profesor</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->lastname }} {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 3. Selector de Secretaria --}}
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold" for="secretary_id">Secretaria Académica</label><span class="text-danger"> *</span>
<select name="secretary_id" id="secretary_id" class="form-select">
    <option value="">Seleccione una Secretaria</option>
    @foreach ($secretaries as $secretary)
        {{-- CORRECTO: Accedemos al nombre a través de la relación user --}}
        <option value="{{ $secretary->id }}" {{ old('secretary_id') == $secretary->id ? 'selected' : '' }}>
            {{ $secretary->user->name ?? 'Secretaria ID: ' . $secretary->id }}
        </option>
    @endforeach
</select>
                    @error('secretary_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            {{-- Lugar y Fecha --}}
            <div class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Lugar y fecha de firma</h4>
                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Lugar</label><span class="text-danger"> *</span>
                    <input type="text" name="lugar_firma" placeholder="Lugar" class="form-control"
                        value="{{ old('lugar_firma') }}">

                    @error('lugar_firma')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Fecha</label><span class="text-danger"> *</span>
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
                    <input type="file" name="doc_afip" accept=".pdf,image/*" placeholder="Constancia de AFIP"
                        class="form-control">

                    @error('doc_afip')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Estatuto de confirmación</label>
                    <input type="file" name="doc_estatuto" accept=".pdf,image/*"
                        placeholder="Estatuto de confirmación" class="form-control">

                    @error('doc_estatuto')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Asignación de autoridades</label>
                    <input type="file" name="doc_autoridades" accept=".pdf,image/*"
                        placeholder="Asignación de autoridades" class="form-control">

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
    
    @vite('resources/js/formAgreements/frameworkAgreement/getCompany.js')
    @vite('resources/js/formAgreements/frameworkAgreement/selectEmployee.js')
@endsection
