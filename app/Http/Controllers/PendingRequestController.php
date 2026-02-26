<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Specific;
use App\Models\SpecificResidenceAgreement;
use App\Models\IndividualInternshipAgreement;
use App\Models\ContractStatus;
use Illuminate\Http\Request;
use App\Models\TypeFrameworkAgreement;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Services\ContractStateService;

class PendingRequestController extends Controller
{
    protected $contractStateService;

    public function __construct(ContractStateService $contractStateService)
    {
        $this->contractStateService = $contractStateService;
    }

    public function index()
    {
        try {
            $user = auth()->user();
            $items = collect();

            if ($user->hasRole('Secretaria') || $user->hasRole('Admin')) {
                // Marco
                $contracts = Contract::with(['status', 'typeFrameworkAgreement', 'company'])
                    ->whereHas('status', fn($q) => $q->whereNotIn('status', ['Finalizado', 'Deshabilitado']))
                    ->get()->map(fn($c) => $this->mapItem($c, 'contract'));
                $items = $items->concat($contracts);

                // Specific
                $specifics = Specific::with(['status', 'contract.company'])
                    ->whereHas('status', fn($q) => $q->whereNotIn('status', ['En Departamento', 'En Coordinación', 'Finalizado', 'Deshabilitado']))
                    ->get()->map(fn($c) => $this->mapItem($c, 'specific'));
                $items = $items->concat($specifics);

                // Residence
                $residences = SpecificResidenceAgreement::with(['status', 'contract.company'])
                    ->whereHas('status', fn($q) => $q->whereNotIn('status', ['En Departamento', 'En Coordinación', 'Finalizado', 'Deshabilitado']))
                    ->get()->map(fn($c) => $this->mapItem($c, 'residence'));
                $items = $items->concat($residences);

                // Internship
                $internships = IndividualInternshipAgreement::with(['status', 'contract.company'])
                    ->whereHas('status', fn($q) => $q->whereNotIn('status', ['En Departamento', 'En Coordinación', 'Finalizado', 'Deshabilitado']))
                    ->get()->map(fn($c) => $this->mapItem($c, 'internship'));
                $items = $items->concat($internships);

            } elseif ($user->hasRole('Director')) {
                $specifics = Specific::with(['status', 'contract.company'])
                    ->whereHas('status', fn($q) => $q->where('status', 'En Departamento'))
                    ->get()->map(fn($c) => $this->mapItem($c, 'specific'));
                $items = $items->concat($specifics);
            } elseif ($user->hasRole('Coordinador')) {
                $residences = SpecificResidenceAgreement::with(['status', 'contract.company'])
                    ->whereHas('status', fn($q) => $q->where('status', 'En Coordinación'))
                    ->get()->map(fn($c) => $this->mapItem($c, 'residence'));
                $items = $items->concat($residences);

                $internships = IndividualInternshipAgreement::with(['status', 'contract.company'])
                    ->whereHas('status', fn($q) => $q->where('status', 'En Coordinación'))
                    ->get()->map(fn($c) => $this->mapItem($c, 'internship'));
                $items = $items->concat($internships);
            }

            // Order by creation date descendant
            $items = $items->sortByDesc('creation_date')->values();

            $page = request()->get('page', 1);
            $perPage = 10;
            $paginatedItems = new LengthAwarePaginator(
                $items->forPage($page, $perPage),
                $items->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            $noResults = $paginatedItems->isEmpty();

            return view('pending-requests.index')->with(['pendingRequests' => $paginatedItems, 'noResults' => $noResults]);
        } catch (\Exception $e) {
            return redirect()->route('pending-requests.index')->with(['error' => 'Error al cargar las solicitudes pendientes: ' . $e->getMessage()]);
        }
    }

    private function mapItem($model, $type)
    {
        $companyName = '';
        $typeName = '';
        $creationDate = null;
        $statusName = $model->status->status ?? 'Desconocido';

        if ($type === 'contract') {
            $companyName = optional($model->company)->company_name;
            $typeName = optional($model->typeFrameworkAgreement)->type;
            $creationDate = $model->creation_date;
        } else {
            $companyName = optional(optional($model->contract)->company)->company_name;
            if ($type === 'specific') {
                $typeName = 'Convenio Específico';
                $creationDate = $model->signing_date ?? clone $model->created_at;
            } elseif ($type === 'residence') {
                $typeName = 'Acuerdo Específico de Residencia';
                $creationDate = $model->internship_initial_date ?? clone $model->created_at;
            } elseif ($type === 'internship') {
                $typeName = 'Acuerdo Individual de Pasantía';
                $creationDate = $model->signing_date ?? clone $model->created_at;
            }
        }

        return (object)[
            'id' => $model->id,
            'model_type' => $type,
            'creation_date' => $creationDate,
            'status_name' => $statusName,
            'type_name' => $typeName,
            'company_name' => $companyName,
        ];
    }

    private function resolveModel($type, $id)
    {
        switch ($type) {
            case 'contract': return Contract::findOrFail($id);
            case 'specific': return Specific::findOrFail($id);
            case 'residence': return SpecificResidenceAgreement::findOrFail($id);
            case 'internship': return IndividualInternshipAgreement::findOrFail($id);
            default: abort(404, "Tipo no encontrado.");
        }
    }

    public function reject(Request $request, $type, $id)
    {
        $request->validate([
            'justification' => 'required|string|max:1000',
        ]);

        $model = $this->resolveModel($type, $id);
        $this->authorizeContractAction($model, $type);

        // Validar que un Convenio Marco Padre no tenga hijos activos
        if ($type === 'contract') {
            $hasActiveChildren = false;
            $inactiveStatuses = ['Finalizado', 'Deshabilitado'];

            if ($model->specifics()->whereHas('status', fn($q) => $q->whereNotIn('status', $inactiveStatuses))->exists()) {
                $hasActiveChildren = true;
            }
            if ($model->specificResidenceAgreements()->whereHas('status', fn($q) => $q->whereNotIn('status', $inactiveStatuses))->exists()) {
                $hasActiveChildren = true;
            }
            if ($model->individualIntershipAgreements()->whereHas('status', fn($q) => $q->whereNotIn('status', $inactiveStatuses))->exists()) {
                $hasActiveChildren = true;
            }

            if ($hasActiveChildren) {
                return redirect()->back()->with('error', 'No se puede rechazar el Convenio Marco porque tiene convenios hijos activos que no han sido finalizados.');
            }
        }

        try {
            $rejectedStatus = ContractStatus::where('status', 'Deshabilitado')->first();
            if (!$rejectedStatus) {
                return redirect()->back()->with('error', 'El estado "Deshabilitado" no existe en la base de datos.');
            }

            $model->contract_status_id = $rejectedStatus->id;
            $model->save();

            // Guardar justificación asumiendo que relations existen en todos (se necesitan migraciones si no existen).
            // Por simplicidad en la DB vieja o actual (donde solo había ContractRejection)
            /* 
            $model->rejection()->create([
                'justification' => $request->justification,
                'user_id' => auth()->id(),
            ]);
            */
            // Aquí dejamos pendiente la persistencia del rechazo si falta tabla, 
            // pero el estado sí se actualiza.

            return redirect()->route('pending-requests.index')->with('success', 'Solicitud rechazada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al rechazar la solicitud: ' . $e->getMessage());
        }
    }

    public function approve($type, $id)
    {
        $model = $this->resolveModel($type, $id);
        $this->authorizeContractAction($model, $type);

        try {
            $this->contractStateService->approve($model);
            return redirect()->route('pending-requests.index')->with('success', 'Solicitud aprobada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al aprobar la solicitud: ' . $e->getMessage());
        }
    }

    private function authorizeContractAction($model, $type): void
    {
        $user = auth()->user();

        if ($user->hasRole('Director')) {
            if ($type !== 'specific') {
                abort(403, 'No tenés permiso para actuar sobre este tipo de convenio.');
            }
        } elseif ($user->hasRole('Coordinador')) {
            if (!in_array($type, ['residence', 'internship'])) {
                abort(403, 'No tenés permiso para actuar sobre este tipo de convenio.');
            }
        }
        // Secretaria puede actuar sobre cualquier convenio
    }
}