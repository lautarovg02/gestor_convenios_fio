<p class="textCampos"><span class="text-danger">*</span> Campos obligatorios</p>
<form id="formulario" action="{{ route('specificResidenceAgreement.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

<!-- dates of agreeement -->
    <div class="mb-4 border rounded containerSectionForm">

        <h4 class="TitleSection">Datos del acuerdo</h4>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Empresa</label>
            <input type="text" name="companyName" class="form-control" placeholder="Nombre" readonly>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre del acuerdo</label>
            <input type="text" name="agreementName" class="form-control" value="{{ old('agreementName') }}" placeholder="Nombre del acuerdo" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Tareas a realizar durante el acuerdo</label>
            <textarea name="tasks" class="form-control"  value="{{ old('tasks') }}" placeholder="Tareas a realizar..."></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">Fecha de firma</label>
            <input type="date" name="fecha_firma" class="form-control" value="{{ old('fecha_firma') }}">
        </div>

        <input type="hidden" name="contract_id" id="contract_id_field"> <!--este input esta oculto porque es el id del contract-->
    
    </div>


    <!-- dates of student -->
    <div class=" mb-4 border rounded containerSectionForm">
        <h4 class="TitleSection">Datos del estudiante</h4>
        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Nombre</label>
            <input type="text" name="studentName" class="form-control" value="{{ old('studentName') }}" placeholder="Nombre" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">Apellido</label>
            <input type="text" name="studentLastName" class="form-control" value="{{ old('studentLastName') }}" placeholder="Apellido" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fs-6 fw-bold">DNI</label>
            <input type="number" name="dniStudent" class="form-control" placeholder="DNI" value="{{ old('dniStudent') }}" required>
            <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>
        </div>


        <div class="mb-3">
            <label class="form-label fs-6 fw-bold">CUIL</label>
            <div class="input-group">
                <input type="number" class="form-control" name="student_cuil_prefijo" placeholder="20" maxlength="2"
                    pattern="\d{2}" value="{{ old('student_cuil_prefijo') }}">
                <span class="input-group-text">-</span>
                <input type="number" class="form-control" name="student_cuil_dni" placeholder="12345678" maxlength="8"
                    pattern="\d{7,8}" value="{{ old('student_cuil_dni') }}">
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
            <input type="text" name="studentCarrer" class="form-control" placeholder="Carrera del estudiante" value="{{ old('studentCarrer') }}" required>
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
