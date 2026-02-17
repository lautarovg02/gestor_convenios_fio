<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractStatus;
use Illuminate\Http\Request;
use App\Models\TypeFrameworkAgreement;



class PendingRequestController extends Controller
{
    /**
     * Muestra una lista de los contratos que se encuentran en estado 'pendiente' de gestión.
     *
     */
    public function index()
    {
        try {
            $excludeStatus = ['Finalizado', 'Deshabilitado']; // Estados a excluir - deshabilitados serian los rechazados.

            $pendingRequests = Contract::whereHas('status', function ($query) use ($excludeStatus) {
            // 'status' es la columna en la tabla contract_statuses, lo bueno de eloquent es que podemos acceder a la tabla contract_statuses a través de la relación hasMany
            $query->whereNotIn('status', $excludeStatus);
        })
        ->orderBy('creation_date', 'desc')
        ->paginate(10);

            if ($pendingRequests->isEmpty()) {
                return view('pending-requests.index')->with(['pendingRequests' => $pendingRequests, 'noResults' => true]);
            }

            return view('pending-requests.index', compact('pendingRequests'));
        } catch (\Exception $e) {
            return redirect()->route('pending-requests.index')->with(['error' => 'Error al cargar las solicitudes pendientes. Inténtalo nuevamente.']);
        }

     


}
    public function reject(Request $request, Contract $contract)
    {
        $request->validate([
            'justification' => 'required|string|max:1000',
        ]);

        try {
            // 1. Buscar el estado 'Deshabilitado'
            $rejectedStatus = ContractStatus::where('status', 'Deshabilitado')->first();

            if (!$rejectedStatus) {
                return redirect()->back()->with('error', 'El estado "Deshabilitado" no existe en la base de datos.');
            }

            // 2. Actualizar el estado del contrato
            $contract->contract_status_id = $rejectedStatus->id;
            $contract->save();

            // 3. Guardar la justificación en la nueva tabla
            $contract->rejection()->create([
                'justification' => $request->justification,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('pending-requests.index')->with('success', 'Solicitud rechazada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al rechazar la solicitud: ' . $e->getMessage());
        }
    }


}