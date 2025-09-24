<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;

class AdminUsersController extends Controller
{

    protected $serviceUsers;

    public function __construct(UserService $serviceUsers)
    {
        $this->serviceUsers = $serviceUsers;
    }
    
public function index()
{
    $teachers = $this->serviceUsers->getAllTeachers();
    $secretaries = $this->serviceUsers->getAllSecretaries();

    return view('adminUsers.index', compact('teachers', 'secretaries'));
}

    public function create()
    {
        return view('adminUsers.create');
    }

    public function edit($id)
    {
        return view('adminUsers.edit', compact('id'));
    }

    public function show($id)
    {
        return view('adminUsers.show', compact('id'));
    }

    public function destroy($id)
    {
        // Lógica para eliminar un usuario
        return redirect()->route('adminUsers.index')->with('success', 'Usuario eliminado correctamente');
    }
}
