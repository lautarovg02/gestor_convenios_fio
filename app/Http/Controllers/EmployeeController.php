<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployee;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    public function index(Company $company)
    {
        $employees = $company->employees()->with('phones')->get();
        return view('employees.index', compact('company', 'employees'));
    }

    public function create()
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEmployee $request, Employee $employee)
    {

        // Obtén los datos validados
        $validated = $request->validated();

        // Actualiza los datos básicos del empleado.
        $employee->update([
            'name'         => $validated['name'],
            'lastname'     => $validated['lastname'],
            'position'     => $validated['position'],
            'dni'          => $validated['dni'],
            'email'        => $request->input('email'),
            'is_represent' => $request->input('is_represent'),
        ]);


        $deletePhoneIds = [];

        if ($request->has('phones') && is_array($request->phones)) {
            foreach ($request->phones as $key => $phoneData) {
                // Si el teléfono está marcado como eliminado, lo agregamos al array
                if (!empty($phoneData['delete']) && $phoneData['delete'] == "1") {
                    $deletePhoneIds[] = $phoneData['id'];
                    continue; // Saltamos el resto de la lógica para este teléfono
                }

                // Si no tiene ID, es un nuevo teléfono
                if (!isset($phoneData['id']) || $key === 'new') {
                    if (!empty(trim($phoneData['number']))) {
                        $employee->phones()->create([
                            'number'      => $phoneData['number'],
                            'employee_id' => $employee->id,
                        ]);
                    }
                } else {
                    $existingPhone = $employee->phones()->find($phoneData['id']);
                    if ($existingPhone) {
                        $existingPhone->update([
                            'number' => $phoneData['number'],
                        ]);
                    }
                }
            }
        }

        //Ejecutar la eliminación de los teléfonos marcados
        if (!empty($deletePhoneIds) && $employee->phones()->count() - count($deletePhoneIds) > 0) {
            $employee->phones()->whereIn('id', $deletePhoneIds)->delete();
        }
        return redirect()->route('companies.employees.index', ['company' => $employee->company_id])
            ->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
