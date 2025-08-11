<p class="textCampos"><span class="text-danger">*</span> Campos obligatorios</p>
<form id="formulario" action="{{ route('specificResidenceAgreement.store') }}" method="POST" enctype="multipart/form-data">
    @csrf


    <div class="mb-4 border rounded containerSectionForm selectCompanySection">
        <h4 class="TitleSection">Seleccionar empresa</h4>
        <p class="textCampos

    <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccionar Empresa</label>
    <select id="selectCompany" name="company_id" class="form-select">
        <option value="">Seleccione una empresa</option>
        @foreach ($companies as $company)
            <option value="{{ $company->id }}">
                {{ $company->denomination }} - {{ $company->cuit }}
            </option>
        @endforeach
    </select>

    </div>


    <!-- dates of agreeement -->
    <div class="mb-4 border rounded containerSectionForm">

        <h4 class="TitleSection">Datos del acuerdo</h4>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre del acuerdo</label>
            <input type="text" name="agreementName" class="form-control" value="{{ old('agreementName') }}"
                placeholder="Nombre del acuerdo" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Tareas a realizar durante el acuerdo</label>
            <textarea name="tasks" class="form-control" placeholder="Tareas a realizar...">{{ old('tasks') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">Fecha de firma</label>
            <input type="date" name="fecha_firma" class="form-control" value="{{ old('fecha_firma') }}">
        </div>

        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">Fecha de inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
        </div>


        <div class="DatesCompany">
            <!--este input esta oculto porque es el id del contract-->
            <input type="hidden" value="{{ old('contract_id') }}" name="contract_id" id="contract_id_field">

            <div class="mb-3">
                <label for="status" class="form-label fs-6 fw-bold">Empresa</label>
                <input type="text" name="companyName" value="{{ old('companyName') }}" class="form-control"
                    placeholder="Nombre" readonly>
                <input type="hidden" value="{{ old('companyId') }}" name="companyId" id="companyId">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label fs-6 fw-bold">Apoderado de la empresa</label>
                <input type="text" name="companyRepresentative" value="{{ old('companyRepresentative') }}"
                    class="form-control" readonly>
            </div>

        </div>

    </div>


    <!-- dates of student -->
    <div class=" mb-4 border rounded containerSectionForm">
        <h4 class="TitleSection">Datos del estudiante</h4>

        
        <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccione el estudiante</label>
        <select id="selectStudent" name="id_student" class="form-select">
            <option value="">Seleccionar estudiante</option>
        </select>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre</label>
            <input type="text" name="studentName" class="form-control" value="{{ old('studentName') }}"
                placeholder="Nombre" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Apellido</label>
            <input type="text" name="studentLastName" class="form-control" value="{{ old('studentLastName') }}"
                placeholder="Apellido" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">DNI</label>
            <input type="number" name="dniStudent" class="form-control" placeholder="DNI"
                value="{{ old('dniStudent') }}" maxlength="8" oninput="validarDigitos(this)" required>
            <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
        </div>


        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">CUIL</label>
            <div class="input-group">
                <input type="number" class="form-control" name="student_cuil_prefijo" placeholder="20" maxlength="2"
                    pattern="\d{2}" value="{{ old('student_cuil_prefijo') }}">
                <span class="input-group-text">-</span>
                <input type="number" class="form-control" name="student_cuil_dni" placeholder="12345678"
                    maxlength="8" pattern="\d{7,8}" oninput="validarDigitos(this)"
                    value="{{ old('student_cuil_dni') }}">
                <span class="input-group-text">-</span>
                <input type="number" class="form-control" name="student_cuil_dv" placeholder="3" maxlength="1"
                    pattern="\d{1}" value="{{ old('student_cuil_dv') }}">
            </div>
        </div>


        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">Email</label>
            <input id="student_email" type="email" placeholder="Email" name="studentEmail" class="form-control"
                value="{{ old('studentEmail') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">Celular</label>
            <input type="number" placeholder="Celular" name="studentCelular" class="form-control"
                value="{{ old('studentCelular') }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Carrera</label>

            <select name="studentCarrer" id="carrer" class="form-select" value="{{ old('studentCarrer') }}"
                required>
                <option value="" disabled selected>Seleccione una carrera</option>
                @foreach ($carrers as $carrer)
                    <option value="{{ $carrer->id }}">{{ $carrer->name }}</option>
                @endforeach
            </select>

        </div>

    </div>


<!-- Datos del tutor -->

    <div class=" mb-4 border rounded containerSectionForm">
        <h4 class="TitleSection">Datos del tutor de la empresa</h4>

        <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccionar Tutor responsable</label>
                <select id="selectEmployee" name="id_employee" class="form-select">
                    <option value="">Seleccionar tutor de la empresa</option>
                </select>


        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre</label>
            <input type="text" name="tutorName" class="form-control" value="{{ old('tutorName') }}"
                placeholder="Nombre" required readonly>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Apellido</label>
            <input type="text" name="tutorLastName" class="form-control" value="{{ old('tutorLastName') }}"
                placeholder="Apellido" required readonly>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">DNI</label>
            <input type="number" name="tutorDni" maxlength="8" oninput="validarDigitos(this)"
                class="form-control" placeholder="DNI" value="{{ old('tutorDni') }}" required readonly>
            <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
        </div>
    </div>

    <div class=" mb-4 border rounded containerSectionForm">
        <h4 class="TitleSection">Datos del tutor de la facultad</h4>
        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre</label>
            <input type="text" name="tutorFacuName" class="form-control" value="{{ old('tutorFacuName') }}"
                placeholder="Nombre" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Apellido</label>
            <input type="text" name="tutorFacuLastName" class="form-control"
                value="{{ old('tutorFacuLastName') }}" placeholder="Apellido" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">DNI</label>
            <input type="number" name="tutorFacuDni" class="form-control" placeholder="DNI"
                value="{{ old('tutorFacuDni') }}" maxlength="9" oninput="validarDigitos(this)" required>
            <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Departamento del tutor</label>
            <select name="departament" id="departament" class="form-select" value="{{ old('departament') }}"
                required>
                <option value="" disabled selected>Seleccione un departamento</option>
                @foreach ($departaments as $departament)
                    <option value="{{ $departament->id }}">{{ $departament->name }}</option>
                @endforeach
            </select>
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
    </div>
</form>


@vite('resources/js/formAgreements/createSpecificResidenceAgreement/formCreate.js')
@vite('resources/js/formAgreements/createSpecificResidenceAgreement/selectEmployee.js')

