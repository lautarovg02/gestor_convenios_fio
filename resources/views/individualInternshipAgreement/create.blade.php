@extends('layouts.app')

@vite('resources/css/form_convenios/formCreateAgreement.css')

@section('content')
<div class="container mt-4">

    <h2 class="text-center mb-5 title fw-bold">CREAR CONVENIO INDIVIDUAL DE PASANTÍA</h2>

    <form action="{{ route('individual-internship-agreements.select-company') }}" method="POST" class="mt-3">
        @csrf

        <div class="row g-4">
            
            {{-- ✅ Columna Empresa --}}
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="card-title mb-3">🏢 Empresa</h4>
                        <p class="card-text text-muted">Seleccioná la empresa con convenio marco de pasantía.</p>

                        <div class="mb-3">
                            <label for="companySelect" class="form-label fw-semibold">Empresa</label>
                            <select id="companySelect" name="company_id" class="form-select" required>
                                <option value="">-- Seleccionar empresa --</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->denomination }} (CUIT: {{ $company->cuit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ✅ Columna Alumno --}}
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="card-title mb-3">🎓 Alumno</h4>
                        <p class="card-text text-muted">Buscá y seleccioná el alumno que formará parte de este convenio.</p>

                        {{-- 🔍 Input búsqueda --}}
                        <div class="input-group mb-3">
                            <input type="text" id="searchStudent" class="form-control" placeholder="Buscar por nombre o DNI">
                            <button type="button" id="btnSearchStudent" class="btn btn-primary">Buscar</button>
                        </div>

                        {{-- 📋 Lista resultados --}}
                        <ul id="studentResults" class="list-group small"></ul>

                        {{-- ✅ Campo oculto con ID de alumno seleccionado --}}
                        <input type="hidden" name="student_id" id="student_id">
                    </div>
                </div>
            </div>

        </div>

        {{-- ✅ Botón Confirmar (fuera de las tarjetas) --}}
        <div class="text-center mt-4">
            <button type="submit" id="btnConfirm" class="btn btn-success btn-lg px-90 fw-bold" disabled>
                 Confirmar y continuar
            </button>
            <p class="text-muted mt-2 small">Seleccioná empresa y alumno para habilitar el botón</p>
        </div>

    </form>
</div>

{{-- ✅ Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // buscar alumno
    $('#btnSearchStudent').on('click', function() {
        let query = $('#searchStudent').val();
        if (query.length < 2) {
            alert('Escribí al menos 2 caracteres');
            return;
        }

        $.get("{{ route('students.search') }}", { q: query }, function(data) {
            $('#studentResults').empty();
            if (data.length === 0) {
                $('#studentResults').append('<li class="list-group-item">❌ No se encontraron alumnos</li>');
            } else {
                data.forEach(student => {
                    $('#studentResults').append(`
                        <li class="list-group-item list-group-item-action" style="cursor:pointer"
                            onclick="selectStudent(${student.id}, '${student.name}', '${student.last_name}', '${student.dni}')">
                            👤 ${student.name} ${student.last_name} <br> 
                            <small class="text-muted">DNI: ${student.dni}</small>
                        </li>
                    `);
                });
            }
        });
    });
});

// seleccionar alumno de la lista
function selectStudent(id, name, last_name, dni) {
    $('#student_id').val(id); 
    $('#studentResults').html(`<li class="list-group-item active">✅ ${name} ${last_name} (DNI: ${dni}) seleccionado</li>`);
    validateForm();
}

// habilitar botón solo si hay empresa y alumno
$('#companySelect').on('change', function() {
    validateForm();
});

function validateForm() {
    if ($('#companySelect').val() && $('#student_id').val()) {
        $('#btnConfirm').prop('disabled', false);
    } else {
        $('#btnConfirm').prop('disabled', true);
    }
}
</script>
@endsection
