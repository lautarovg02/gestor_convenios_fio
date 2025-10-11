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

    public function index(Request $request)
{
    $query = User::with(['teacher', 'secretary', 'role']);

    // Filtro por select de nombre
    if ($request->filled('name')) {
        $query->where('id', $request->name);
    }

    // Filtro por select de email
    if ($request->filled('email')) {
        $query->where('id', $request->email);
    }

    // Filtro general de búsqueda
    if ($request->filled('search')) {
        $search = $request->search;
    
        $query->where('email', 'like', "%{$search}%")
              ->orWhereRelation('teacher', 'name', 'like', "%{$search}%")
              ->orWhereRelation('teacher', 'lastname', 'like', "%{$search}%")
              ->orWhereRelation('secretary', 'username', 'like', "%{$search}%");
    }    

    $users = $query->orderByDesc('id')->paginate(10); // 10 por página


    // Para los selects de filtros
    $allUsers = User::orderByDesc('id')->get();

    $noResults = $users->isEmpty();

    return view('adminUsers.index', compact('users', 'allUsers', 'noResults'));
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
