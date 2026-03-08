@extends('layouts.app')

@vite('resources\css\form_convenios\formCreateAgreement.css')

@section('content')
    <div class="container mt-4" "> <!-- ancho máximo fijo -->
            <h2 class="mb-3 text-center">CREAR CONVENIO MARCO DE PASANTIA</h2>

     @if ($errors->has('errorExistsContract'))
        <div class="alert alert-danger">
            <span>{{ $errors->first('errorExistsContract') }}</span>
        </div>
    @elseif ($errors->has('error'))
        <div class="alert alert-danger">
            <span>Error al enviar el formulario</span>
        </div>
        @endif


        <p class="textCampos"><span class="text-danger">*</span> Campos obligatorios</p>

        <form id="formulario" action="{{ route('frameworkInternshipAgreement.store') }}" method="POST"
            enctype="multipart/form-data">
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

            </div>


            <div class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Datos de la contraparte</h4>


                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Razón Social</label><span class="text-danger"> *</span>
                    <input type="text" placeholder="Razón Social" name="razon_social" class="form-control"
                        value="{{ old('razon_social') }}" required readonly>

                    {{-- Validación de errores --}}

                    @error('razon_social')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label d-block fs-6 fw-bold">Ámbito <span class="text-danger"> *</span></label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="ambito" value="nacional" id="ambitoNacional"
                            checked required>
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
                    <label class="form-label fs-6 fw-bold">CUIT</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control" name="contraparte_cuit" placeholder="CUIT"
                            value="{{ old('contraparte_cuit') }}" required readonly>

                    </div>

                    @error('contraparte_cuit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Rubro</label><span class="text-danger"> *</span>
                    <input type="text" placeholder="Rubro de la empresa" name="contraparte_rubro" class="form-control"
                        value="{{ old('contraparte_rubro') }}" required>

                    {{-- Validación de errores --}}

                    @error('contraparte_rubro')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Titular / Representante Legal / Apoderado</label> <span
                        class="text-danger"> *</span>

                    <select id="selectEmployeeTitular" name="titular" class="form-select" required>
                        <option value="">Seleccione un titular</option>
                    </select>
                    @error('titular')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label d-block fs-6 fw-bold">Cláusula de Confidencialidad</label><span
                        class="text-danger"> *</span>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="confidencialidad" value="si" id="confSi"
                            checked required>
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



            {{-- Dirección --}}
            <div class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Dirección de contraparte</h4>


                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">País</label><span class="text-danger"> *</span>
                    <input type="text" name="pais" placeholder="País" class="form-control"
                        value="{{ old('pais') }}" required>

                    {{-- Validación de errores --}}

                    @error('pais')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label fs-6 fw-bold">Provincia</label><span class="text-danger"> *</span>
                    <input type="text" id="provincia" name="provincia" placeholder="Provincia" class="form-control"
                        value="{{ old('provincia') }}" required>
                    

                    @error('provincia')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Ciudad</label><span class="text-danger"> *</span>

                    <input type="text" id="ciudad" name="localidad" placeholder="Ciudad" class="form-control"
                        value="{{ old('localidad') }}" required>

                    {{-- Validación de errores --}}

                    @error('localidad')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Código Postal</label><span class="text-danger"> *</span>
                    <input type="text" name="codigo_postal" placeholder="Codigo postal" class="form-control"
                        value="{{ old('codigo_postal') }}" required>

                    {{-- Validación de errores --}}

                    @error('codigo_postal')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Calle</label><span class="text-danger"> *</span>
                    <input type="text" name="calle" placeholder="Calle" class="form-control"
                        value="{{ old('calle') }}" required>

                    {{-- Validación de errores --}}

                    @error('calle')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Numero</label><span class="text-danger"> *</span>
                    <input type="number" name="nro_calle" placeholder="Nro. de calle" class="form-control"
                        value="{{ old('nro_calle') }}" required>

                    {{-- Validación de errores --}}

                    @error('nro_calle')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

            </div>


            {{-- Representante Contacto --}}
            <div id="contactRepresentativeCard" class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Representante de contacto</h4>

                <div id="contactSelectWrapper" class="d-none">
                    <label class="form-label fs-6 fw-bold" for="selectEmployeeContact">Seleccionar Representante existente</label><span
                        class="text-danger"> *</span>
                    <select id="selectEmployeeContact" name="id_employee" class="form-select mb-3">
                        <option value="">Seleccionar representante de contacto</option>
                    </select>
                </div>

                <div id="noContactMsg" class="alert alert-info d-none">
                    <i class="bi bi-info-circle me-2"></i>
                    La empresa no tiene representantes registrados. Completá los datos para crear uno nuevo.
                </div>

                <div class="containerInputNameLastName">
                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Nombre(s)</label><span class="text-danger"> *</span>
                        <input id="contact_nombre" type="text" name="contact_nombre" placeholder="Nombre"
                            class="form-control" value="{{ old('contact_nombre') }}" required>

                        @error('contact_nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Apellido(s)</label><span class="text-danger"> *</span>
                        <input id="contact_apellido" type="text" placeholder="Apellido" name="contact_apellido"
                            class="form-control" value="{{ old('contact_apellido') }}" required>

                        @error('contact_apellido')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">DNI</label><span class="text-danger"> *</span>
                    <input id="contact_dni" type="number" name="contact_dni" placeholder="DNI" class="form-control"
                        value="{{ old('contact_dni') }}" required>
                    <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>

                    @error('contact_dni')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">CUIL</label><span class="text-danger"> *</span>

                    <input type="number" id="contact_cuil" class="form-control" name="contact_cuil" placeholder="CUIL"
                        value="{{ old('contact_cuil') }}" required>

                    @error('contact_cuil')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Celular</label><span class="text-danger"> *</span>
                    <input type="number" id="contact_celular" name="contact_celular" class="form-control"
                        value="{{ old('contact_celular') }}" placeholder="Numero de celular" required>

                    @error('contact_celular')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Email</label><span class="text-danger"> *</span>
                    <input id="contact_email" type="email" placeholder="Email" name="contact_email"
                        class="form-control" value="{{ old('contact_email') }}" required>

                    @error('contact_email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Empresa</label><span class="text-danger"> *</span>
                    <input id="contact_empresa" type="text" placeholder="Empresa" name="contact_empresa"
                        class="form-control" value="{{ old('contact_empresa') }}" required readonly>

                    @error('contact_empresa')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Cargo</label><span class="text-danger"> *</span>
                    <input id="contact_cargo" type="text" placeholder="Cargo en empresa" name="contact_cargo"
                        class="form-control" value="{{ old('contact_cargo') }}" required>

                    @error('contact_cargo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

            </div>


            {{-- Representante Firma --}}
            <div id="firmaRepresentativeCard" class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Representante de firma</h4>

                <div class="containerCheckSameRepresentative">
                    <input type="checkbox" id="sameRepresentative"> El representante de contacto es también el de firma
                </div>

                <div id="firmaSelectWrapper" class="d-none mt-2">
                    <label class="form-label fs-6 fw-bold" for="selectEmployeeFirma">Seleccionar Representante existente</label><span
                        class="text-danger"> *</span>
                    <select id="selectEmployeeFirma" name="id_employee_firma" class="form-select mb-3">
                        <option value="">Seleccionar representante de firma</option>
                    </select>
                </div>

                <div id="noFirmaMsg" class="alert alert-info d-none">
                    <i class="bi bi-info-circle me-2"></i>
                    La empresa no tiene representantes registrados. Completá los datos para crear uno nuevo.
                </div>

                <div class="containerInputNameLastName">

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Nombre(s)</label><span class="text-danger"> *</span>
                        <input id="firma_nombre" type="text" name="firma_nombre" placeholder="Nombre"
                            class="form-control" value="{{ old('firma_nombre') }}" required>

                        @error('firma_nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-6 fw-bold">Apellido(s)</label><span class="text-danger"> *</span>
                        <input id="firma_apellido" type="text" name="firma_apellido" placeholder="Apellido"
                            class="form-control" value="{{ old('firma_apellido') }}" required>

                        {{-- Validación de errores --}}

                        @error('firma_apellido')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">DNI</label><span class="text-danger"> *</span>
                    <input id="firma_dni" type="number" name="firma_dni" placeholder="DNI" class="form-control"
                        value="{{ old('firma_dni') }}" required>
                    <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>

                    @error('firma_dni')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Razon Social de Empresa</label><span class="text-danger">
                        *</span>
                    <input id="firma_razon_social" type="text" placeholder="Razon social"
                        name="firma_empresa_razon_social" class="form-control"
                        value="{{ old('firma_empresa_razon_social') }}" readonly required>

                    {{-- Validación de errores --}}

                    @error('firma_empresa_razon_social')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Cargo</label><span class="text-danger"> *</span>
                    <input id="firma_cargo" type="text" name="firma_cargo" placeholder="Cargo en empresa"
                        class="form-control" value="{{ old('firma_cargo') }}" required>

                    {{-- Validación de errores --}}

                    @error('firma_cargo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Email</label><span class="text-danger"> *</span>
                    <input id="firma_email" type="email" name="firma_email" placeholder="Email" class="form-control"
                        value="{{ old('firma_email') }}" required>

                    {{-- Validación de errores --}}

                    @error('firma_email')
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
                        value="{{ old('lugar_firma') }}" required>

                    {{-- Validación de errores --}}

                    @error('lugar_firma')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Fecha</label><span class="text-danger"> *</span>
                    <input type="date" name="fecha_firma" class="form-control" value="{{ old('fecha_firma') }}"
                        required>

                    {{-- Validación de errores --}}

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
                    <input type="file" name="doc_afip" placeholder="Constancia de AFIP" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Estatuto de confirmación</label>
                    <input type="file" name="doc_estatuto" placeholder="Estatuto de confirmación"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold">Asignación de autoridades</label>
                    <input type="file" name="doc_autoridades" placeholder="Asignación de autoridades"
                        class="form-control">
                </div>

            </div>

            {{-- Responsables Institucionales FIO --}}
            <div class="mb-4 border rounded containerSectionForm">
                <h4 class="TitleSection">Responsables Institucionales (FIO)</h4>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold" for="teacher_id">Docente Responsable</label><span class="text-danger"> *</span>
                    <select id="teacher_id" name="teacher_id" class="form-select" required>
                        <option value="">Seleccionar docente responsable</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->lastname }}, {{ $teacher->name }} — DNI: {{ $teacher->dni }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold" for="rector_id">Rector</label><span class="text-danger"> *</span>
                    <select id="rector_id" name="rector_id" class="form-select" required>
                        <option value="">Seleccionar rector</option>
                        @foreach ($rectors as $rector)
                            <option value="{{ $rector->id }}" {{ old('rector_id') == $rector->id ? 'selected' : '' }}>
                                {{ $rector->lastname }}, {{ $rector->name }} — DNI: {{ $rector->dni }}
                            </option>
                        @endforeach
                    </select>
                    @error('rector_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fs-6 fw-bold" for="secretary_id">Secretaria/o</label><span class="text-danger"> *</span>
                    <select id="secretary_id" name="secretary_id" class="form-select" required>
                        <option value="">Seleccionar secretaria/o</option>
                        @foreach ($secretaries as $secretary)
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
    @vite('resources/js/formAgreements/frameworkInternshipAgreement/getCompany.js')
    @vite('resources/js/formAgreements/frameworkInternshipAgreement/selectEmployee.js')
@endsection
