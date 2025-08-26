<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployee;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Employee;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Company $company)
    {
        $employees = $company->employees()->with('phones')->get();
        return view('employees.index', compact('company', 'employees'));
    }

    public function create(Company $company)
    {
        return view('employees.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(StoreEmployee $request, Company $company)
    {
        // Recuperamos los datos validados directamente
        $validated = $request->validated();

        // Creamos el empleado
        $employee = new Employee([
            'company_id'   => $company->id,
            'name'         => $validated['name'],
            'lastname'     => $validated['lastname'],
            'dni'          => $validated['dni'],
            'cuil'         => $validated['cuil'] ?? null,
            'position'     => $validated['position'],
            'email'        => $validated['email'] ?? null,
            'is_represent' => $request->has('is_represent') ? 1 : 0,
        ]);

        //    $employee->company_id = $company->id;
        $employee->save();

        // ✅ Guardar UN teléfono si vino
        if (!empty($validated['phone'])) {
            $employee->phones()->create([
                'number' => $validated['phone'],
                'is_primary' => true,
            ]);
        }

        return redirect()
            ->route('companies.employees.index', $company->id)
            ->with('success', 'Empleado agregado correctamente.');
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
        $validated = $request->validated();

        $employee->update([
            'name'         => $validated['name'],
            'lastname'     => $validated['lastname'],
            'dni'          => $validated['dni'],          // <-- valor, no reglas
            'cuil'         => $validated['cuil'] ?? null,
            'position'     => $validated['position'],
            'email'        => $validated['email'] ?? null,
            'is_represent' => $validated['is_represent'] ?? 0,
        ]);

        // --- teléfonos (tu lógica tal como la tenías) ---
        $deletePhoneIds = [];

        if ($request->has('phones') && is_array($request->phones)) {
            foreach ($request->phones as $key => $phoneData) {
                if (!empty($phoneData['delete']) && $phoneData['delete'] == "1") {
                    $deletePhoneIds[] = $phoneData['id'] ?? null;
                    continue;
                }

                if (!isset($phoneData['id']) || $key === 'new') {
                    if (!empty(trim($phoneData['number'] ?? ''))) {
                        $employee->phones()->create([
                            'number'      => $phoneData['number'],
                            'employee_id' => $employee->id,
                        ]);
                    }
                } else {
                    $existingPhone = $employee->phones()->find($phoneData['id']);
                    if ($existingPhone) {
                        $existingPhone->update([
                            'number' => $phoneData['number'] ?? '',
                        ]);
                    }
                }
            }
        }

        if (!empty($deletePhoneIds)) {
            $employee->phones()->whereIn('id', array_filter($deletePhoneIds))->delete();
        }

        return redirect()
            ->route('companies.employees.index', ['company' => $employee->company_id])
            ->with('success', 'Empleado actualizado correctamente.');
    }



    public function destroy(Employee $employee)
    {
        $companyId = $employee->company_id;

        // 1) Bloqueo por referencias en contratos
        $referenciado = Contract::query()
            ->where('contact_employee_id', $employee->id)
            ->orWhere('representative_employee_id', $employee->id)
            ->exists();

        if ($referenciado) {
            return redirect()
                ->route('companies.employees.index', ['company' => $companyId])
                ->withErrors(['employee' => 'No se puede eliminar: el empleado está vinculado a convenios.']);
        }

        // 2) Eliminar dependencias primero (teléfonos)
        DB::transaction(function () use ($employee) {
            // asumiendo relación hasMany phones() en Employee
            $employee->phones()->delete();   // borra filas en employee_phones
            $employee->delete();             // ahora sí, borra el empleado
        });

        return redirect()
            ->route('companies.employees.index', ['company' => $companyId])
            ->with('success', 'Empleado eliminado correctamente.');
    }

     public function getEmployeesByCompany(int $companyId)
    {
        $employees = Employee::where('company_id', $companyId)
            ->with('phones')
            ->get();

        return response()->json($employees);

    }

    public function getEmployeeById($id)
    {
        $emp = Employee::with([
                'company:id,denomination,company_name',
                'phones:id,number,employee_id'
            ])
            ->findOrFail($id, ['id','name','lastname','dni','email','cuil','position','company_id']);
    
        return response()->json([
            'id'       => $emp->id,
            'name'     => $emp->name,
            'lastname' => $emp->lastname,
            'dni'      => $emp->dni,
            'email'    => $emp->email,
            'cuil'     => $emp->cuil,
            'position' => $emp->position,
            'company'  => [
                'denomination' => $emp->company?->denomination,
                'company_name' => $emp->company?->company_name,
            ],
            
            'phones'   => $emp->phones->pluck('number')->values(),
        ]);
    }
    
}
