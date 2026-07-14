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
            $includeStatus = ['Deshabilitado']; 
            $items = collect();

            // Marco
            $contracts = \App\Models\Contract::with(['status', 'typeFrameworkAgreement', 'company', 'rejections.user'])
                ->whereHas('status', fn($q) => $q->whereIn('status', $includeStatus))
                ->get()->map(fn($c) => $this->mapItem($c, 'contract'));
            $items = $items->concat($contracts);

            // Specific
            $specifics = \App\Models\Specific::with(['status', 'contract.company', 'rejections.user'])
                ->whereHas('status', fn($q) => $q->whereIn('status', $includeStatus))
                ->get()->map(fn($c) => $this->mapItem($c, 'specific'));
            $items = $items->concat($specifics);

            // Residence
            $residences = \App\Models\SpecificResidenceAgreement::with(['status', 'contract.company', 'rejections.user'])
                ->whereHas('status', fn($q) => $q->whereIn('status', $includeStatus))
                ->get()->map(fn($c) => $this->mapItem($c, 'residence'));
            $items = $items->concat($residences);

            // Internship
            $internships = \App\Models\IndividualInternshipAgreement::with(['status', 'contract.company', 'rejections.user'])
                ->whereHas('status', fn($q) => $q->whereIn('status', $includeStatus))
                ->get()->map(fn($c) => $this->mapItem($c, 'internship'));
            $items = $items->concat($internships);

            // Order by rejection date (using the last rejection's created_at, or creation_date if not exist)
            $items = $items->sortByDesc(function($item) {
                return $item->rejection_date ?? $item->creation_date;
            })->values();

            $page = request()->get('page', 1);
            $perPage = 10;
            $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
                $items->forPage($page, $perPage),
                $items->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            $noResults = $paginatedItems->isEmpty();

            return view('rejected-requests.index')->with(['rejectedRequests' => $paginatedItems, 'noResults' => $noResults]);
        } catch (\Exception $e) {
            return redirect()->route('rejected-requests.index')->with(['error' => 'Error al cargar las solicitudes rechazadas: ' . $e->getMessage()]);
        }
    }

    private function mapItem($model, $type)
    {
        $companyName = '';
        $typeName = '';
        $creationDate = null;
        $statusName = $model->status->status ?? 'Desconocido';
        $rejection = $model->rejections->last();

        if ($type === 'contract') {
            $companyName = optional($model->company)->company_name;
            $typeName = optional($model->typeFrameworkAgreement)->type;
            $creationDate = $model->creation_date;
        } else {
            $companyName = optional(optional($model->contract)->company)->company_name;
            if ($type === 'specific') {
                $typeName = 'Convenio Específico';
                $creationDate = $model->signing_date ?? $model->created_at;
            } elseif ($type === 'residence') {
                $typeName = 'Acuerdo Específico de Residencia';
                $creationDate = $model->internship_initial_date ?? $model->created_at;
            } elseif ($type === 'internship') {
                $typeName = 'Acuerdo Individual de Pasantía';
                $creationDate = $model->signing_date ?? $model->created_at;
            }
        }

        return (object)[
            'id' => $model->id,
            'model_type' => $type,
            'creation_date' => $creationDate,
            'status_name' => $statusName,
            'type_name' => $typeName,
            'company_name' => $companyName,
            'justification' => $rejection ? $rejection->justification : 'Sin justificación',
            'rejected_by' => $rejection && $rejection->user ? $rejection->user->name : 'N/A',
            'rejection_date' => $rejection ? $rejection->created_at : null,
        ];
    }



}