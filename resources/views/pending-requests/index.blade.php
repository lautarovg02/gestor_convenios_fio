@extends('layouts.app')

@section('title', 'Solicitudes Pendientes')

@section('content')
<div class="container content-with-footer-buffer">
    
    {{-- TÍTULO Y BREADCRUMB --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Gestión de Convenios</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item text-muted">Convenios</li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Solicitudes Pendientes</li>
                </ol>
            </nav>
        </div>
    </div>
    
    {{-- Mensajes (Mantenidos) --}}
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @elseif (Session::get('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    {{-- TABLA EN VUELTA EN CARD Y CON CLASES DE ESTILO --}}
    <div class="card shadow-sm rounded">
        <div class="table-responsive rounded shadow-sm table-scrollable-container">
            
           <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center text-nowrap ">Fecha de inicio</th>
                                <th class="text-center text-nowrap">Estado</th>
                                <th class="text-center text-nowrap">Tipo</th>
                                <th class="text-center text-nowrap ">Razón Social</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
             
                   
                <tbody>
                            @foreach ($pendingRequests as $request)
                                <tr>
                                    <td class="text-center ">{{ $request->creation_date }}</td>
                                    <td class="text-center ">{{ $request->status_name }}</td>
                                    <td class="text-center">{{ $request->type_name }}</td>
                                    <td class="text-center">{{ $request->company_name}}</td>
                                    <td class="text-nowrap text-center">
                                        @can('aprobar rechazar solicitudes')
                                            @php
                                                $blockedForSecretary = auth()->user()->hasRole('Secretaria')
                                                    && in_array($request->status_name, ['En Coordinación', 'En Departamento']);
                                            @endphp
                                            @if(!$blockedForSecretary)
                                                <form action="{{ route('contracts.approve', ['type' => $request->model_type, 'id' => $request->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success me-1">
                                                        Aprobar <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal"
                                                        data-contract-id="{{ $request->id }}"
                                                        data-model-type="{{ $request->model_type }}">
                                                    Rechazar <i class="bi bi-x-circle"></i>
                                                </button>
                                            @else
                                                <span class="badge bg-secondary" title="En espera de revisión por otro área">
                                                    <i class="bi bi-hourglass-split me-1"></i>Pendiente de otra área
                                                </span>
                                            @endif
                                        @endcan
                                        <button type="button" class="btn btn-sm btn-primary me-1"  >
                                            Descargar  <i class="bi bi-download me-1"></i>
                                        </button>
                                          
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </table>
            
        </div>
        
        {{-- Paginación, si la colección es paginada --}}
        @if ($pendingRequests instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="card-footer d-flex justify-content-center">
                {{ $pendingRequests->appends(request()->all())->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        @endif

    </div>
    
   
    
</div>
</div>

{{-- MODAL DE RECHAZO --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectModalLabel">Rechazar Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p>Por favor, ingrese el motivo del rechazo para esta solicitud.</p>
                    <div class="mb-3">
                        <label for="justification" class="form-label">Justificación</label>
                        <textarea class="form-control" id="justification" name="justification" rows="4" required maxlength="1000"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rejectModal = document.getElementById('rejectModal');
        rejectModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var contractId = button.getAttribute('data-contract-id');
            var modelType = button.getAttribute('data-model-type');
            var form = document.getElementById('rejectForm');
            
            // Construir la URL de acción dinámicamente
            var actionUrl = "{{ route('contracts.reject', ['type' => ':type', 'id' => ':id']) }}";
            actionUrl = actionUrl.replace(':type', modelType).replace(':id', contractId);
            
            form.action = actionUrl;
        });
    });
</script>

@endsection