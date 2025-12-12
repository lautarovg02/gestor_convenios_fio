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
            $pendingStatuses = [1,2,3,4,5,11,8,6]; // Estados que representan 'pendiente' de gestión
            $pendingRequests = Contract::whereIn('contract_status_id', $pendingStatuses)
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