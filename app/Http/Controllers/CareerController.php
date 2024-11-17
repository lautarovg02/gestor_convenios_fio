<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Department;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @App\Models\Career;
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $careers = Career::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('department', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
        })
            ->paginate(10);
        return view('careers.index', compact('careers'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coordinators = collect([(object) ['id' => 1, 'name' => 'Coordinador 1'], (object) ['id' => 2, 'name' => 'Coordinador 2'], (object) ['id' => 3, 'name' => 'Coordinador 3']]);

        $departaments = Department::orderBy('name', 'ASC')->get();
        return view('careers.create', compact('departaments', 'coordinators'));
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
    public function show(Career $career)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Career $career)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Career $career)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Career $career)
    {
        //
    }
}
