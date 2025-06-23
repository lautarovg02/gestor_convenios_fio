@extends('layouts.app')

@vite('resources\css\form_convenios\formCreateAgreement.css')

@section('content')
    <div class="container mt-4" "> <!-- ancho máximo fijo -->
                <h2 class="mb-3 text-center">CREAR CONVENIO MARCO DE PASANTIA</h2>

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

                <form id="formulario" action="{{ route('frameworkInternshipAgreement.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Representante Contacto --}}
                    <div class="mb-4 border rounded containerSectionForm">
                        <h4 class="TitleSection">Representante de contacto</h4>
                        {{-- Cada campo en bloque separado y ancho completo --}}
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
                            <label class="form-label fs-6 fw-bold">DNI</label>
                            <input id="contact_dni" type="number" name="contact_dni" placeholder="DNI" class="form-control" value="{{ old('contact_dni') }}">
                            <small class="form-hint">Ingresar <b>DNI</b> sin puntos.</small>

                            @error('contact_dni')
                                <div class="text-danger">{{ $message }}</div>  
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">CUIL</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="contact_cuil_prefijo" placeholder="20" maxlength="2"
                                    pattern="\d{2}" value="{{ old('contact_cuil_prefijo') }}" required>
                                <span class="input-group-text">-</span>
                                <input type="number" class="form-control" name="contact_cuil_dni" placeholder="12345678" maxlength="8"
                                    pattern="\d{7,8}" value="{{ old('contact_cuil_dni') }}" required>
                                <span class="input-group-text">-</span>
                                <input type="number" class="form-control" name="contact_cuil_dv" placeholder="3" maxlength="1"
                                    pattern="\d{1}" value="{{ old('contact_cuil_dv') }}" required>
                            </div>
                            <div class="form-text">Formato: XX-XXXXXXXX-X</div>

                                @error('contact_cuil_prefijo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @error('contact_cuil_dni')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @error('contact_cuil_dv')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @error('contact_cuil')
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
                        
                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Empresa</label>
                            <input id="contact_empresa" type="text" placeholder="Empresa" name="contact_empresa" class="form-control" value="{{ old('contact_empresa') }}" required>
                        
                            @error('contact_empresa')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Cargo</label>
                            <input id="contact_cargo" type="text" placeholder="Cargo en empresa" name="contact_cargo" class="form-control" value="{{ old('contact_cargo') }}" required">
                        
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
                            <label class="form-label fs-6 fw-bold">Razon Social de Empresa</label>
                            <input id="firma_razon_social" type="text" placeholder="Razon social" name="firma_empresa_razon_social" class="form-control" value="{{ old('firma_empresa_razon_social') }}">

                            {{-- Validación de errores --}}
                            
                            @error('firma_empresa_razon_social')
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

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Email</label>
                            <input id="firma_email" type="email" name="firma_email" placeholder="Email" class="form-control" value="{{ old('firma_email') }}">

                                {{-- Validación de errores --}}

                                @error('firma_email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                        </div>

                    </div>


                    {{-- Contraparte --}}
                    <div class=" mb-4 border rounded containerSectionForm">
                        <h4 class="TitleSection">Contraparte</h4>
                        
                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Razón Social</label>
                            <input type="text" placeholder="Razón Social" name="razon_social" class="form-control" value="{{ old('razon_social') }}">

                            {{-- Validación de errores --}}
                            
                            @error('razon_social')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block fs-6 fw-bold">Ámbito</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ambito" value="nacional"
                                    id="ambitoNacional" checked>
                                <label class="form-check-label" for="ambitoNacional">Nacional</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ambito" value="internacional"
                                    id="ambitoInternacional" >
                                <label class="form-check-label" for="ambitoInternacional">Internacional</label>
                            </div>
                                @error('ambito')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                        </div>


                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">CUIT</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="contraparte_cuit_prefijo" placeholder="23" maxlength="2"
                                    pattern="\d{2}" value="{{ old('contraparte_cuit_prefijo') }}">
                                <span class="input-group-text">-</span>
                                <input type="number" class="form-control" name="contraparte_cuit_dni" placeholder="12345678"
                                    maxlength="8" pattern="\d{7,8}" value="{{ old('contraparte_cuit_dni') }}">
                                <span class="input-group-text">-</span>
                                <input type="number" class="form-control" name="contraparte_cuit_dv" placeholder="9" maxlength="1"
                                    pattern="\d{1}" value="{{ old('contraparte_cuit_dv') }}">
                            </div>
                            <div class="form-text">Formato: XX-XXXXXXXX-X</div>

                                @error('contraparte_cuit_prefijo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @error('contraparte_cuit_dni')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @error('contraparte_cuit_dv')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                @error('contraparte_cuit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            
                        </div>


                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Rubro</label>
                            <input type="text" placeholder="Rubro de la empresa" name="contraparte_rubro" class="form-control" value="{{ old('contraparte_rubro') }}">

                            {{-- Validación de errores --}}
                            
                            @error('contraparte_rubro')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Titular / Representante Legal / Apoderado</label>
                            <input type="text" placeholder="Titular / Representante Legal / Apoderado" name="titular"
                                class="form-control" value="{{ old('titular') }}">
                            @error('titular')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block fs-6 fw-bold">Cláusula de Confidencialidad</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="confidencialidad" value="si"
                                    id="confSi" checked>
                                <label class="form-check-label" for="confSi">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="confidencialidad" value="no"
                                    id="confNo" >
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
                            <label class="form-label fs-6 fw-bold">País</label>
                            <input type="text" name="pais" placeholder="País" class="form-control" value="{{ old('pais') }}">

                            {{-- Validación de errores --}}

                            @error('pais')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="mb-3">

                            <label class="form-label fs-6 fw-bold">Provincia</label>
                            <select id="provincia" name="provincia" class="form-select">
                                <option value="">Seleccione una provincia</option>
                                @foreach($provincias as $provincia)
                                    <option value="{{ $provincia['nombre'] }}">{{ $provincia['nombre'] }}</option>
                                @endforeach
                            </select>

                            {{-- Validación de errores --}}

                            @error('provincia')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Ciudad</label>
                            
                            <select id="ciudad" name="localidad" class="form-select" disabled value="{{ old('ciudad') }}">
                                <option value="">Seleccione una ciudad</option>
                            </select>
                        
                            {{-- Validación de errores --}}

                            @error('localidad')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Código Postal</label>
                            <input type="number" name="codigo_postal" placeholder="Codigo postal" class="form-control" value="{{ old('codigo_postal') }}">

                            {{-- Validación de errores --}}

                            @error('codigo_postal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Calle</label>
                            <input type="text" name="calle" placeholder="Calle" class="form-control" value="{{ old('calle') }}">

                            {{-- Validación de errores --}}

                            @error('calle')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fs-6 fw-bold">Numero</label>
                            <input type="number" name="nro_calle" placeholder="Nro. de calle" class="form-control" value="{{ old('nro_calle') }}">

                            {{-- Validación de errores --}}

                            @error('nro_calle')
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
