<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractStatus;
use Illuminate\Http\Request;
use App\Models\TypeFrameworkAgreement;



use App\Services\ContractStateService;

class PendingRequestController extends Controller
{
    protected $contractStateService;

    public function __construct(ContractStateService $contractStateService)
    {
        $this->contractStateService = $contractStateService;
    }
    /**
     * Muestra una lista de los contratos pendientes, filtrada según el rol del usuario.
     *
     * - Secretaria: ve todos los convenios pendientes.
     * - Director: ve solo los convenios específicos (tienen registros en `specifics`).
     * - Coordinador: ve solo los convenios individuales (tienen registros en `specificResidenceAgreements` o `individualIntershipAgreements`).
     */
    public function index()
    {
        try {
            $excludeStatus = ['Finalizado', 'Deshabilitado'];
            $user = auth()->user();

            $query = Contract::whereHas('status', function ($q) use ($excludeStatus) {
                $q->whereNotIn('status', $excludeStatus);
            });

            // Filtrar según el rol del usuario
            if ($user->hasRole('Director')) {
                // Director: solo convenios que tienen al menos un registro en `specifics`
                $query->whereHas('specifics');
            } elseif ($user->hasRole('Coordinador')) {
                // Coordinador: solo convenios individuales (residencia o pasantía individual)
                $query->where(function ($q) {
                    $q->whereHas('specificResidenceAgreements')
                      ->orWhereHas('individualIntershipAgreements');
                });
            }
            // Secretaria y Admin ven todos (sin filtro adicional)

            $pendingRequests = $query->orderBy('creation_date', 'desc')->paginate(10);

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

        // Verificar que el usuario tiene permiso para actuar sobre este tipo de convenio
        $this->authorizeContractAction($contract);

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


    public function approve(Contract $contract)
    {
        // Verificar que el usuario tiene permiso para actuar sobre este tipo de convenio
        $this->authorizeContractAction($contract);

        try {
            $this->contractStateService->approve($contract);
            return redirect()->route('pending-requests.index')->with('success', 'Solicitud aprobada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al aprobar la solicitud: ' . $e->getMessage());
        }
    }
    /**
     * Verifica que el usuario autenticado tiene permiso para actuar sobre el tipo de convenio.
     *
     * - Secretaria: puede actuar sobre cualquier convenio.
     * - Director: solo puede actuar sobre convenios que tienen registros en `specifics`.
     * - Coordinador: solo puede actuar sobre convenios individuales (residencia o pasantía).
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function authorizeContractAction(Contract $contract): void
    {
        $user = auth()->user();

        if ($user->hasRole('Director')) {
            // Director solo puede actuar sobre convenios específicos
            if ($contract->specifics()->count() === 0) {
                abort(403, 'No tenés permiso para actuar sobre este tipo de convenio.');
            }
        } elseif ($user->hasRole('Coordinador')) {
            // Coordinador solo puede actuar sobre convenios individuales
            $esIndividual = $contract->specificResidenceAgreements()->exists()
                         || $contract->individualIntershipAgreements()->exists();
            if (!$esIndividual) {
                abort(403, 'No tenés permiso para actuar sobre este tipo de convenio.');
            }
        }
        // Secretaria puede actuar sobre cualquier convenio (sin restricción)
    }
}