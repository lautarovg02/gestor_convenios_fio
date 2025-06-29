<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Support\Collection;

class DepartamentService{ 


    public function getAllDepartments(): Collection
    {
        return Department::all();
    }
}