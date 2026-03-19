<p class="textCampos"><span class="text-danger">*</span> Campos obligatorios</p>
<form id="formulario" action="{{ route('specificResidenceAgreement.store') }}" method="POST" enctype="multipart/form-data">
    @csrf


    <div class="mb-4 border rounded containerSectionForm selectCompanySection">
        <h4 class="TitleSection">Seleccionar empresa</h4>  
    <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccionar Empresa</label><span class="text-danger"> *</span>
            <select id="selectCompany" name="company_id" class="form-select">
                <option value="">Seleccione una empresa</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->denomination }} - (CUIT: {{ $company->cuit }})</option>
                @endforeach
            </select>

    </div>


    <!-- dates of agreeement -->
    <div class="mb-4 border rounded containerSectionForm">

        <h4 class="TitleSection">Datos del acuerdo</h4>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre del acuerdo</label><span class="text-danger">
                *</span>
            <input type="text" name="agreementName" class="form-control" value="{{ old('agreementName') }}"
                placeholder="Nombre del acuerdo" required>

            @error('agreementName')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Tareas a realizar durante el acuerdo</label><span
                class="text-danger"> *</span>
            <textarea name="tasks" class="form-control" placeholder="Tareas a realizar...">{{ old('tasks') }}</textarea>

            @error('tasks')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>


        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">Fecha de firma</label><span class="text-danger"> *</span>
            <input type="date" name="fecha_firma" class="form-control" value="{{ old('fecha_firma') }}" required>

            @error('fecha_firma')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">Fecha de inicio</label><span class="text-danger"> *</span>
            <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>


            @error('fecha_inicio')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="DatesCompany">
            <!--este input esta oculto porque es el id del contract-->
            <input type="hidden" value="{{ old('contract_id') }}" name="contract_id" id="contract_id_field">

            <div class="mb-3">
                <label for="status" class="form-label fs-6 fw-bold">Empresa</label>
                <input type="text" name="companyName" value="{{ old('companyName') }}" class="form-control"
                    placeholder="Nombre" readonly>
                <input type="hidden" value="{{ old('companyId') }}" name="companyId" id="companyId">


                @error('companyName')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label fs-6 fw-bold">Apoderado de la empresa</label>
                <input type="text" name="companyRepresentative" value="{{ old('companyRepresentative') }}"
                    placeholder="Apoderado de la empresa" class="form-control" readonly>
            </div>

            @error('companyRepresentative')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>



    <!-- dates of student -->
    <div class=" mb-4 border rounded containerSectionForm">
        <h4 class="TitleSection">Datos del estudiante</h4>


        <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccione el estudiante</label><span
            class="text-danger"> *</span>
        <select id="selectStudent" name="select_id_student" class="form-select" required>
        
            <option value="">Seleccionar estudiante</option>
            @foreach ($students as $student)
                <option value="{{ $student->dni }}">{{ $student->last_name }} {{ $student->name }} -
                    (DNI: {{ $student->dni }})</option>
            @endforeach

            <input type="hidden" value="{{ old('studentIdHidden') }}" name="studentIdHidden" id="studentIdHidden">
        </select>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre</label><span class="text-danger"> *</span>
            <input type="text" name="studentName" class="form-control" value="{{ old('studentName') }}" readonly
                placeholder="Nombre" required>


            @error('studentName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Apellido</label><span class="text-danger"> *</span>
            <input type="text" name="studentLastName" class="form-control" value="{{ old('studentLastName') }}"
                readonly placeholder="Apellido" required>


            @error('studentLastName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">DNI</label><span class="text-danger"> *</span>
            <input type="number" name="dniStudent" class="form-control" placeholder="DNI" readonly
                value="{{ old('dniStudent') }}" maxlength="8" oninput="validarDigitos(this)" required>
            <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>


            @error('dniStudent')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">CUIL</label><span class="text-danger"> *</span>
            <div class="input-group">
                <input type="number" class="form-control" name="studentCuil" placeholder="20456278932"
                    maxlength="11" readonly value="{{ old('studentCuil') }}" required>

            </div>


            @error('studentCuil')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">Email</label><span class="text-danger"> *</span>
            <input id="student_email" type="email" placeholder="Email" name="studentEmail" class="form-control"
                readonly value="{{ old('studentEmail') }}" required>


            @error('studentEmail')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">Celular</label><span class="text-danger"> *</span>
            <input type="number" placeholder="Celular" name="studentCelular" class="form-control" readonly
                value="{{ old('studentCelular') }}" required>


            @error('studentCelular')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Carrera</label><span class="text-danger"> *</span>

            <select name="studentCarrer" id="carrer" class="form-select" value="{{ old('studentCarrer') }}"
                required>
                <option value="" disabled selected>Seleccione una carrera</option>
                @foreach ($carrers as $carrer)
                    <option value="{{ $carrer->id }}">{{ $carrer->name }}</option>
                @endforeach
            </select>



            @error('studentCarrer')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

    </div>


    <!-- Datos del tutor -->

    <div class=" mb-4 border rounded containerSectionForm">
        <h4 class="TitleSection">Datos del tutor de la empresa</h4>

        <label class="form-label fs-6 fw-bold" for="selectCompany">Seleccionar Tutor responsable</label><span
            class="text-danger"> *</span>
        <select id="selectEmployee" name="id_employee" class="form-select" required>
            <option value="">Seleccionar tutor de la empresa</option>
        </select>


        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre</label>
            <input type="text" name="tutorName" class="form-control" value="{{ old('tutorName') }}"
                placeholder="Nombre" required readonly>


            @error('tutorName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Apellido</label>
            <input type="text" name="tutorLastName" class="form-control" value="{{ old('tutorLastName') }}"
                placeholder="Apellido" required readonly>


            @error('tutorLastName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">DNI</label>
            <input type="number" name="tutorDni" maxlength="8" oninput="validarDigitos(this)"
                class="form-control" placeholder="DNI" value="{{ old('tutorDni') }}" required readonly>
            <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>

            @error('tutorDni')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


    </div>

    <div class=" mb-4 border rounded containerSectionForm">
        <h4 class="TitleSection">Datos del tutor de la facultad</h4>
        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre</label>
            <input type="text" name="tutorFacuName" class="form-control" value="{{ old('tutorFacuName') }}"
                placeholder="Nombre" required readonly>


            @error('tutorFacuName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Apellido</label>
            <input type="text" name="tutorFacuLastName" class="form-control"
                value="{{ old('tutorFacuLastName') }}" placeholder="Apellido" required readonly>


            @error('tutorFacuLastName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">DNI</label>
            <input type="number" name="tutorFacuDni" class="form-control" placeholder="DNI" readonly
                value="{{ old('tutorFacuDni') }}" maxlength="9" oninput="validarDigitos(this)" required>
            <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>


            @error('tutorFacuDni')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Departamento del tutor</label><span
                class="text-danger"> *</span>
            <select name="departament" id="departament" class="form-select" value="{{ old('departament') }}"
                required>
                <option value="" disabled selected>Seleccione un departamento</option>
                @foreach ($departaments as $departament)
                    <option value="{{ $departament->id }}">{{ $departament->name }}</option>
                @endforeach
            </select>


            @error('departament')
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
    </div>
</form>


@vite('resources/js/formAgreements/createSpecificResidenceAgreement/formCreate.js')
@vite('resources/js/formAgreements/createSpecificResidenceAgreement/selectEmployee.js')
@vite('resources/js/formAgreements/createSpecificResidenceAgreement/SelectStudent.js')
