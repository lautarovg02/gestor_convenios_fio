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
            $pendingRequests = Contract::whereHas('status', function ($query) {
            // Filtro para que el nombre del estado NO sea 'Finalizado'
            // 'status' es la columna en la tabla contract_statuses, lo bueno de eloquent es que podemos acceder a la tabla contract_statuses a través de la relación hasMany
            $query->where('status', '!=', 'Finalizado');
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



}