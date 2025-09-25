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
        //   $teachers = $this->serviceUsers->getAllTeachers(); //Obtengo todos los teachers
        // $secretaries = $this->serviceUsers->getAllSecretaries(); // Obtengo todos los secretaries
        $users = User::select('id', 'email', 'role_id')
            ->with([
                'role:id,name',
                'teacher:id,user_id,name,lastname',
                'secretary:id,user_id,username'
            ])
            ->orderByDesc('id')
            ->get();


        return view('adminUsers.index', compact('users'));
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
        $user = User::findOrFail($id);

        // Guardarraíles
        if (auth()->id() === $user->id) {
            return back()->with('error', 'No podés eliminarte a vos mismo.');
        }

        // Evitar borrar al último admin (ajustá según tu modelo de rol)
        if ($user->rol?->name === 'admin') {
            $remaining = User::whereHas('rol', fn($q) => $q->where('name', 'admin'))
                ->where('id', '!=', $user->id)
                ->count();
            if ($remaining === 0) {
                return back()->with('error', 'No se puede eliminar al último administrador.');
            }
        }

        $user->delete(); // dispara ON DELETE CASCADE en secretaries/teachers
        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}
