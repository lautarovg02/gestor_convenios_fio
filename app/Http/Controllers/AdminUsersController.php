<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use DB;

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

    public function show(User $user)
    {
        // Asegurate que $user->role exista (relación role)
        $roleName = optional($user->role)->name;
    
        if ($roleName === 'teacher') {
            // Si tenés una ruta para teachers.show
            if ($user->teacher) {
                return redirect()->route('teachers.show', $user->teacher->id);
            }
    
            // Fallback: si relacion teacher no existe, mostrar la vista con mensaje
            return view('adminUsers.show', [
                'user' => $user,
                'warning' => 'El usuario tiene rol teacher pero no tiene datos en la relación teacher.'
            ]);
        }
    
        // Si es secretary (o cualquier otro), mostramos la vista de adminUsers.show
        return view('adminUsers.show', compact('user'));
    }


    public function destroy(User $user)
    {
        // No permitir que te borres a vos mismo
        if (auth()->check() && auth()->id() === $user->id) {
            return back()->with('error', 'No podés eliminarte a vos mismo.');
        }
    
        // Evitar borrar al último admin (ajustá el nombre del rol si corresponde)
        if ($user->role?->name === 'admin') {
            $remaining = User::whereHas('role', function($q) {
                $q->where('name', 'admin');
            })->where('id', '!=', $user->id)->count();
    
            if ($remaining === 0) {
                return back()->with('error', 'No se puede eliminar al último administrador.');
            }
        }
    
        DB::beginTransaction();
        try {
            // Si existen relaciones one-to-one, borrarlas explícitamente (si no usás ON DELETE CASCADE)
            // Ajustá los nombres de relaciones si tus métodos se llaman distinto (teacher(), secretary()).
            if (method_exists($user, 'teacher') && $user->teacher) {
                $user->teacher()->delete();
            }
            if (method_exists($user, 'secretary') && $user->secretary) {
                $user->secretary()->delete();
            }
    
            // Finalmente borrar el usuario
            $user->delete();
    
            DB::commit();
            return back()->with('success', 'Usuario eliminado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Error al eliminar usuario: '.$e->getMessage());
            return back()->with('error', 'Ocurrió un error al intentar eliminar el usuario.');
        }
    }
    
    
}
