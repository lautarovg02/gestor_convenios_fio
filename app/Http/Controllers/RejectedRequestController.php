<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractStatus;
use Illuminate\Http\Request;
use App\Models\TypeFrameworkAgreement;



class RejectedRequestController extends Controller
{
    public function index()
    {
        try {
            $includeStatus = ['Deshabilitado']; // Estados a incluir - deshabilitados serian los rechazados.

            $rejectedRequests = Contract::whereHas('status', function ($query) use ($includeStatus) {
            // 'status' es la columna en la tabla contract_statuses, lo bueno de eloquent es que podemos acceder a la tabla contract_statuses a través de la relación hasMany
            $query->whereIn('status', $includeStatus);
        })
        ->orderBy('creation_date', 'desc')
        ->paginate(10);

            if ($rejectedRequests->isEmpty()) {
                return view('rejected-requests.index')->with(['rejectedRequests' => $rejectedRequests, 'noResults' => true]);
            }

            return view('rejected-requests.index', compact('rejectedRequests'));
        } catch (\Exception $e) {
            return redirect()->route('rejected-requests.index')->with(['error' => 'Error al cargar las solicitudes rechazadas. Inténtalo nuevamente.']);
        }


}



}