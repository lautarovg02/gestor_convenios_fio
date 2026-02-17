<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contract;
use App\Models\ContractStatus;

class ApprovedRequestController extends Controller
{
    public function index()
    {
        try {
            // Estados que NO queremos ver en aprobados: 'Finalizado' y 'Deshabilitado', y tampoco 'Pendiente' (que es implícito si no tiene estado o es null, pero aquí asumimos que ya tienen estado)
            // En realidad, queremos ver todo lo que NO sea Pendiente (asumiendo pendiente es null o un estado inicial), ni Rechazado, ni Finalizado.
            // Según la lógica, Pendiente es cuando no ha sido aprobado.
            // Los estados de "Aprobado" son todos los intermedios: SEVyT, En Departamento, etc.
            
            $excludeStatus = ['Finalizado', 'Deshabilitado']; 
            
            // Asumiendo que 'Pendiente' es un estado que no está en la lista de arriba, pero los contratos recién creados pueden tener null o un estado específico.
            // Si 'Pendiente' no existe como status en la BD, filtramos los que tengan status_id != null y status not in exclude.
            
            $approvedRequests = Contract::whereHas('status', function ($query) use ($excludeStatus) {
                $query->whereNotIn('status', $excludeStatus);
            })
            // También deberíamos excluir los que conceptualmente son "Pendientes" si existe ese estado explícito.
            // Pero según el plan, aprobados son los "En curso".
            ->orderBy('creation_date', 'desc')
            ->paginate(10);

            if ($approvedRequests->isEmpty()) {
                return view('approved-requests.index')->with(['approvedRequests' => $approvedRequests, 'noResults' => true]);
            }

            return view('approved-requests.index', compact('approvedRequests'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with(['error' => 'Error al cargar las solicitudes aprobadas.']);
        }
    }
}
