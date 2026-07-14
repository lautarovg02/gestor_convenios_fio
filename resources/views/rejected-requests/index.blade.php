@extends('layouts.app')

@section('title', 'Solicitudes Rechazadas')

@section('content')
<div class="container content-with-footer-buffer">
    
    {{-- TÍTULO Y BREADCRUMB --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Gestión de Convenios</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item text-muted">Convenios</li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Solicitudes Rechazadas</li>
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
                                <th class="text-center">Justificación</th>
                            </tr>
                        </thead>
             
                   
                <tbody>
                            @foreach ($rejectedRequests as $request)
                                <tr>
                                    <td class="text-center ">{{ $request->creation_date }}</td>
                                    <td class="text-center ">{{ $request->status_name }}</td>
                                    <td class="text-center">{{ $request->type_name }}</td>
                                    <td class="text-center">{{ $request->company_name }}</td>
                                    <td class="text-center">
                                        {{ $request->justification }}
                                        <br>
                                        <small class="text-muted">Por: {{ $request->rejected_by }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </table>
            
        </div>
        
        {{-- Paginación, si la colección es paginada --}}
        @if ($rejectedRequests instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="card-footer d-flex justify-content-center">
                {{ $rejectedRequests->appends(request()->all())->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        @endif

    </div>
    
   
    
</div>
@endsection