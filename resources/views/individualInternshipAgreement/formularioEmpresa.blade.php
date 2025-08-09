@extends('layouts.app')

@vite('resources/css/form_convenios/formCreateAgreement.css')

@section('content')
<div class="container mt-4">
 
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

  <p class="textCampos"><span class="text-danger">*</span> Campos obligatorios</p>
  <h2 class="title text-center mb-4">CREAR CONVENIO INDIVIDUAL DE PASANTÍA</h2>

  <form action="{{ route('individual-internship-agreements.store') }}" method="POST">

    @csrf
{{-- Guarda oculto el id del contrato para luego guardarlo en la bd--}}
 
    <input type="hidden" name="contract_id" value="{{ $contract->id }}">

<input type="hidden" name="student_id" value="{{ $student->id ?? '' }}">
    {{-- Sección: Empresa --}}
    <div class="mb-4 border rounded containerSectionForm p-3">
      <h4 class="TitleSection">Empresa</h4>

      <div class="mb-3">
        <label class="form-label fw-bold">Denominación (Razón social)</label>
              <input type="text" name="company_denomination" class="form-control" value="{{ $company->denomination }}" readonly>
      </div>

    

      <div class="mb-3">
        <label class="form-label fw-bold">CUIT</label>
        <input type="text" name="company_cuit" class="form-control" value="{{ $company->cuit }}" readonly>
    
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Sector o Actividad</label>
        <input type="text" name="company_sector" class="form-control" value="{{ $company->sector }}" readonly>
       
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Calle (Domicilio)</label>
        <input type="text" name="company_street" class="form-control" value="{{ $company->street }}" readonly>
      </div>
      
      <div class="mb-3">
        <label class="form-label fw-bold">Número</label>
        <input type="text" name="company_number" class="form-control" 
        value="{{ $company->number }}" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Ciudad</label>
        <input type="text" name="company_city" class="form-control" 
       value="{{ old('company_city', $company->city ?? '') }}" readonly>

      </div>
     
      

      <div class="mb-3">
        <label class="form-label fw-bold">Representante</label>
        <input type="text" name="company_representante" class="form-control" value="{{ old('company_representante', $representante['name'] ?? '') }}" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">CUIT del representante</label>
        <input type="text" name="company_representante_cuit" class="form-control" value="{{ old('company_representante_cuit', $representante['cuit'] ?? '') }}" readonly>
        @error('company_representante_cuit')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>

{{-- Sección: Pasante --}}
<div class="mb-4 border rounded containerSectionForm p-3">
  <h4 class="TitleSection">Pasante</h4>

  {{-- Nombre --}}
  <div class="mb-3">
    <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
    <input type="text" name="student_name" class="form-control" 
           value="{{ old('student_name', $student->name ?? '') }}" readonly>
    @error('student_name')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- Apellido --}}
  <div class="mb-3">
    <label class="form-label fw-bold">Apellido <span class="text-danger">*</span></label>
    <input type="text" name="student_last_name" class="form-control" 
           value="{{ old('student_last_name', $student->last_name ?? '') }}" readonly>
    @error('student_last_name')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- DNI --}}
  <div class="mb-3">
    <label class="form-label fw-bold">DNI <span class="text-danger">*</span></label>
    <input type="number" name="student_dni" class="form-control" 
           value="{{ old('student_dni', $student->dni ?? '') }}" readonly>
    @error('student_dni')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- CUIL --}}
  <div class="mb-3">
    <label class="form-label fw-bold">CUIL <span class="text-danger">*</span></label>
    <div class="input-group">
      @php
          $cuil = $student->cuil ?? '';
          $prefijo = substr($cuil, 0, 2);
          $dniCuil = substr($cuil, 2, 8);
          $dv = substr($cuil, -1);
      @endphp
      <input type="text" name="student_cuil_prefijo" class="form-control" maxlength="2" 
             value="{{ old('student_cuil_prefijo', $prefijo) }}" readonly>
      <span class="input-group-text">-</span>
      <input type="text" name="student_cuil_dni" class="form-control" maxlength="8" 
             value="{{ old('student_cuil_dni', $dniCuil) }}" readonly>
      <span class="input-group-text">-</span>
      <input type="text" name="student_cuil_dv" class="form-control" maxlength="1" 
             value="{{ old('student_cuil_dv', $dv) }}" readonly>
    </div>
    @error('student_cuil_prefijo')<div class="text-danger">{{ $message }}</div>@enderror
    @error('student_cuil_dni')<div class="text-danger">{{ $message }}</div>@enderror
    @error('student_cuil_dv')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- Email --}}
  <div class="mb-3">
    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
    <input type="email" name="student_email" class="form-control" 
           value="{{ old('student_email', $student->email ?? '') }}" readonly>
    @error('student_email')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- Teléfono --}}
  <div class="mb-3">
    <label class="form-label fw-bold">Teléfono <span class="text-danger">*</span></label>
    <input type="number" name="student_phone" class="form-control" 
           value="{{ old('student_phone', $student->phone_numb ?? '') }}" readonly>
    @error('student_phone')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- Carrera --}}
  <div class="mb-3">
    <label class="form-label fw-bold">Carrera <span class="text-danger">*</span></label>
    <input type="text" name="student_career" class="form-control" 
           value="{{ old('student_career', $student->career ?? '') }}" readonly>
    @error('student_career')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- Calle --}}
  <div class="mb-3">
    <label class="form-label fw-bold">Calle (Domicilio)<span class="text-danger">*</span></label>
    <input type="text" name="student_domicilio_calle" class="form-control" 
           value="{{ old('student_domicilio_calle', $student->street ?? '') }}" readonly>
    @error('student_domicilio_calle')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- Número --}}
  <div class="mb-3">
    <label class="form-label fw-bold">Número <span class="text-danger">*</span></label>
    <input type="text" name="student_domicilio_numero" class="form-control" 
           value="{{ old('student_domicilio_numero', $student->number ?? '') }}" readonly>
    @error('student_domicilio_numero')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- Ciudad --}}
  <div class="mb-3">
    <label class="form-label fw-bold">Ciudad <span class="text-danger">*</span></label>
    <input type="text" name="student_ciudad" class="form-control" 
           value="{{ old('student_ciudad', $student->city ?? '') }}" readonly>
    @error('student_ciudad')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
</div>



    {{-- Sección: Datos de la Pasantía --}}
    <div class="mb-4 border rounded containerSectionForm p-3">
      <h4 class="TitleSection">Datos de la Pasantía</h4>

    
<div class="mb-3">
    <label class="form-label fw-bold">Fecha de firma del convenio marco</label>
    <input type="date" name="fecha_firma_marco" class="form-control"
           value="{{ $contract->signing_date ? \Carbon\Carbon::parse($contract->signing_date)->format('Y-m-d') : '' }}" readonly>
</div>
    

      <div class="mb-3">
        <label class="form-label fw-bold">Área de pasantía <span class="text-danger">*</span></label>
        <input type="text" name="area_pasantia" class="form-control" value="{{ old('area_pasantia') }}" required>
        @error('area_pasantia')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Sitio donde hará la pasantía <span class="text-danger">*</span></label>
        <input type="text" name="sitio_pasantia" class="form-control" value="{{ old('sitio_pasantia') }}" required>
        @error('sitio_pasantia')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Tareas <span class="text-danger">*</span></label>
        <textarea name="tareas" class="form-control" required>{{ old('tareas') }}</textarea>
        @error('tareas')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Periodo (en meses) <span class="text-danger">*</span></label>
        <input type="number" name="periodo_meses" class="form-control" value="{{ old('periodo_meses') }}" min="1" max="24" required>
        @error('periodo_meses')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Inicio de pasantía <span class="text-danger">*</span></label>
        <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
        @error('fecha_inicio')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Monto acordado <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="remuneracion_monto" class="form-control" value="{{ old('remuneracion_monto') }}" min="0" required>
        @error('remuneracion_monto')
          <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>

    {{-- Sección: Tutor, Docente y Lugar/Fecha juntos --}}
<div class="mb-4 border rounded containerSectionForm p-3">

  {{-- Datos del tutor --}}
  <h4 class="TitleSection">Datos del tutor</h4>
  <div class="mb-3">
    <label class="form-label fw-bold">Nombre y Apellido <span class="text-danger">*</span></label>
    <input type="text" name="tutor_empresa" class="form-control" value="{{ old('tutor_empresa') }}" required>
    @error('tutor_empresa')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>
  <div class="mb-3">
    <label class="form-label fs-6 fw-bold">CUIT <span class="text-danger">*</span></label>
    <div class="input-group">
      <input type="text" class="form-control @error('tutor_cuil_prefijo') is-invalid @enderror" name="tutor_cuil_prefijo" placeholder="00" maxlength="2" pattern="\d{2}" value="{{ old('tutor_cuil_prefijo') }}" required>
      <span class="input-group-text">-</span>
      <input type="text" class="form-control @error('tutor_cuil_dni') is-invalid @enderror" name="tutor_cuil_dni" placeholder="12345678" maxlength="8" pattern="\d{7,8}" value="{{ old('tutor_cuil_dni') }}" required>
      <span class="input-group-text">-</span>
      <input type="text" class="form-control @error('tutor_cuil_dv') is-invalid @enderror" name="tutor_cuil_dv" placeholder="0" maxlength="1" pattern="\d{1}" value="{{ old('tutor_cuil_dv') }}" required>
    </div>
    <div class="form-text">Formato: XX-XXXXXXXX-X</div>
    @error('tutor_cuil_prefijo')<div class="text-danger">{{ $message }}</div>@enderror
    @error('tutor_cuil_dni')<div class="text-danger">{{ $message }}</div>@enderror
    @error('tutor_cuil_dv')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- Datos del docente --}}
  <h4 class="TitleSection mt-4">Datos del docente</h4>
  <div class="mb-3">
    <label class="form-label fw-bold">Nombre y Apellido <span class="text-danger">*</span></label>
    <input type="text" name="docente_nombre" class="form-control" value="{{ old('docente_nombre') }}" required>
    @error('docente_nombre')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>
  <div class="mb-3">
    <label class="form-label fs-6 fw-bold">CUIT <span class="text-danger">*</span></label>
    <div class="input-group">
      <input type="text" class="form-control @error('docente_cuil_prefijo') is-invalid @enderror" name="docente_cuil_prefijo" placeholder="00" maxlength="2" pattern="\d{2}" value="{{ old('docente_cuil_prefijo') }}" required>
      <span class="input-group-text">-</span>
      <input type="text" class="form-control @error('docente_cuil_dni') is-invalid @enderror" name="docente_cuil_dni" placeholder="12345678" maxlength="8" pattern="\d{7,8}" value="{{ old('docente_cuil_dni') }}" required>
      <span class="input-group-text">-</span>
      <input type="text" class="form-control @error('docente_cuil_dv') is-invalid @enderror" name="docente_cuil_dv" placeholder="0" maxlength="1" pattern="\d{1}" value="{{ old('docente_cuil_dv') }}" required>
    </div>
    @error('docente_cuil_prefijo')<div class="text-danger">{{ $message }}</div>@enderror
    @error('docente_cuil_dni')<div class="text-danger">{{ $message }}</div>@enderror
    @error('docente_cuil_dv')<div class="text-danger">{{ $message }}</div>@enderror
  </div>

  {{-- Lugar y Fecha del Convenio Individual --}}
  <h4 class="TitleSection mt-4">Lugar y Fecha del Convenio Individual</h4>
  <div class="mb-3">
    <label class="form-label fw-bold">Fecha del Convenio <span class="text-danger">*</span></label>
    <input type="date" name="fecha_convenio" class="form-control" value="{{ old('fecha_convenio') }}" required>
    @error('fecha_convenio')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>
  <div class="mb-3">
    <label class="form-label fw-bold">Lugar (ciudad o sede) <span class="text-danger">*</span></label>
    <input type="text" name="lugar_convenio" class="form-control" value="{{ old('lugar_convenio') }}" required>
    @error('lugar_convenio')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>
</div>


    {{-- Botón de Envío --}}
    <div class="d-flex justify-content-end mb-5">
      <button type="submit" class="btn btn-success">Guardar convenio</button>
     
    </div>

  </form>
</div>
@endsection
