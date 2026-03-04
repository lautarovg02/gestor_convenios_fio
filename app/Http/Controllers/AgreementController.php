<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\TypeFrameworkAgreement;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $typeFrameworkAgreement = TypeFrameworkAgreement::orderBy('type', 'ASC')->get();
        $statuses               = ContractStatus::where('id', '!=', 10)->orderBy('status', 'ASC')->get();

        $filterType   = $request->input('type', '');
        $filterStatus = $request->input('status', '');
        $filterSearch = $request->input('search', '');

        $query = Contract::with([
                'company', 'status', 'typeFrameworkAgreement',
                'specifics.status',
                'specificResidenceAgreements.status',
                'individualIntershipAgreements.status',
            ])
            ->where('contract_status_id', '!=', 10)
            ->orderBy('creation_date', 'desc');

        // --- Filtro por tipo (ID de type_framework_agreement) ---
        if ($filterType !== '') {
            $query->where('type_framework_agreement_id', (int) $filterType);
        }

        // --- Filtro por estado (marco o cualquier hijo) ---
        if ($filterStatus !== '') {
            $query->where(function ($q) use ($filterStatus) {
                $q->whereHas('status', fn($sq) => $sq->where('status', $filterStatus))
                  ->orWhereHas('specifics.status', fn($sq) => $sq->where('status', $filterStatus))
                  ->orWhereHas('individualIntershipAgreements.status', fn($sq) => $sq->where('status', $filterStatus))
                  ->orWhereHas('specificResidenceAgreements.status', fn($sq) => $sq->where('status', $filterStatus));
            });
        }

        // --- Búsqueda por empresa (company_name o cuit) ---
        if ($filterSearch !== '') {
            $query->whereHas('company', function ($q) use ($filterSearch) {
                $q->where('company_name', 'like', "%{$filterSearch}%")
                  ->orWhere('cuit', 'like', "%{$filterSearch}%");
            });
        }

        $agreements = $query->paginate(10)->withQueryString();

        return view('agreements.index', compact('agreements', 'typeFrameworkAgreement', 'statuses', 'filterType'))
            ->with('noResults', $agreements->isEmpty());
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
   //
}


    /**
     * Display the specified resource.
     */
 
public function show(string $id)
{
    try {
        // Busca el contrato por su ID. Si no lo encuentra, lanza una excepción.
        $agreement = Contract::findOrFail($id);

        // Puedes usar 'load' para cargar las relaciones que vas a usar en la vista,
        // aunque Eloquent las cargará automáticamente si las accedes (lazy loading).
        // Cargar explícitamente (eager loading) puede ser más eficiente:
        $agreement->load([
            'company',
            'secretary',
            'teacher',
            'contactEmployee',
            'representativeEmployee',
            'rectorTeacher',
            'status',
            'typeFrameworkAgreement'
        ]);

        return view('agreements.show', compact('agreement'));
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Manejar caso donde el ID no existe
        return redirect()->route('agreements.index')->with(['error' => 'Convenio no encontrado.']);
    } catch (\Exception $e) {
        // Manejar otros errores
        return redirect()->route('agreements.index')->with(['error' => 'Error al cargar el detalle del convenio.']);
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
