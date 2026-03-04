<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Secretary;
use App\Models\Teacher;
// Usamos el modelo de Spatie para los roles
use Spatie\Permission\Models\Role; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; 
use App\Services\UserService;

class AdminUsersController extends Controller
{
    protected $serviceUsers;

    public function __construct(UserService $serviceUsers)
    {
        $this->serviceUsers = $serviceUsers;
    }

    public function index(Request $request)
    {
        // CORRECCIÓN 1: Cambiamos 'role' (singular) por 'roles' (plural, de Spatie)
        $query = User::with(['teacher', 'secretary', 'roles']);

        // Filtro por ID (name select)
        if ($request->filled('name')) {
            $query->where('id', $request->name);
        }

        // Filtro por ID (email select)
        if ($request->filled('email')) {
            $query->where('id', $request->email);
        }

        // Filtro general de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
    
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhereRelation('teacher', 'name', 'like', "%{$search}%")
                  ->orWhereRelation('teacher', 'lastname', 'like', "%{$search}%");
            });
        }    

        $users = $query->orderByDesc('id')->paginate(10); 
        $allUsers = User::orderByDesc('id')->get();
        $noResults = $users->isEmpty();

        return view('adminUsers.index', compact('users', 'allUsers', 'noResults'));
    }

    public function create()
    {
        // Spatie guarda los roles en la tabla 'roles'
        $roles = Role::all(); 
        return view('adminUsers.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // --- 1. Validaciones ---
        // Validamos que el role_id exista en la tabla de roles de Spatie
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_id'  => ['required', 'exists:roles,id'], // Validamos que el ID del rol exista

            // Validaciones condicionales (buscamos el nombre del rol según el ID enviado)
            'docente_nombre'   => [Rule::requiredIf(fn() => Role::find($request->role_id)?->name === 'teacher'), 'nullable', 'string', 'max:40'],
            'docente_apellido' => [Rule::requiredIf(fn() => Role::find($request->role_id)?->name === 'teacher'), 'nullable', 'string', 'max:40'],
            'dni'              => [Rule::requiredIf(fn() => Role::find($request->role_id)?->name === 'teacher'), 'nullable', 'integer', 'unique:teachers,dni'],
            'cuil'             => ['nullable', 'string', 'max:20', 'unique:teachers,cuil'],
            'facultad'         => ['nullable', 'string', 'max:20'],
            'is_rector'        => ['nullable', 'boolean'],
            'is_dean'          => ['nullable', 'boolean'],
            
            // Secretaria ya no tiene username propio, usa el del User
        ]);

        DB::beginTransaction();

        try {
            // --- 2. Crear Usuario (SIN role_id) ---
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                // 'role_id' => ... ¡ELIMINADO! Spatie no usa esto.
            ]);
            
            // --- 3. Asignar Rol con Spatie ---
            $role = Role::findById($validated['role_id']); // Buscamos el rol por ID
            $user->assignRole($role->name); // Se lo asignamos al usuario

            // --- 4. Crear Perfil Asociado ---
            if ($role->name === 'Secretaria') {
                Secretary::create([
                    'user_id' => $user->id,
                    // 'username' => ... ELIMINADO (Ya no existe en la BD)
                ]);
            } elseif ($role->name === 'Docente') {
                Teacher::create([
                    'user_id'   => $user->id,
                    'name'      => $validated['docente_nombre'],
                    'lastname'  => $validated['docente_apellido'],
                    'dni'       => $validated['dni'],
                    'cuil'      => $validated['cuil'],
                    'faculty'   => $validated['facultad'],
                    'is_rector' => $request->boolean('is_rector'),
                    'is_dean'   => $request->boolean('is_dean'),
                ]);
            }
            
            DB::commit();

            return redirect()->route('adminUsers.index')
                ->with('success', 'Usuario y perfil creado con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage()); // Descomentar para debug
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        // Cargamos roles para el select si quisieras cambiar el rol (opcional)
        // Por ahora lo dejamos simple
        return view('adminUsers.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // 1. Obtener rol actual con Spatie
        // getRoleNames devuelve una colección, tomamos el primero
        $currentRole = $user->getRoleNames()->first(); 
        
        $rules = [
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'name'  => ['required', 'string', 'max:255'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ];

        // Reglas según el rol que tiene asignado
        if ($currentRole === 'teacher') {
            $rules['teacher_name']     = ['required', 'string', 'max:255'];
            $rules['teacher_lastname'] = ['required', 'string', 'max:255'];
            $rules['teacher_dni']      = ['nullable', 'string', 'max:50'];
            $rules['teacher_cuil']     = ['nullable', 'string', 'max:50'];
            $rules['teacher_faculty']  = ['nullable', 'string', 'max:255'];
        } 
        // Secretaria ya no tiene campos extra que validar (username se fue)

        $data = $request->validate($rules);

        DB::beginTransaction();
        try {
            // 2. Actualizar User
            $updateData = [
                'name'  => $data['name'],
                'email' => strtolower(trim($data['email'])),
            ];
            
            if ($request->filled('new_password')) {
                $updateData['password'] = Hash::make($data['new_password']);
            }
            
            $user->update($updateData);

            // 3. Actualizar Perfil
            if ($currentRole === 'teacher' && $user->teacher) {
                $user->teacher->update([
                    'name'     => $data['teacher_name'],
                    'lastname' => $data['teacher_lastname'],
                    'dni'      => $data['teacher_dni'],
                    'cuil'     => $data['teacher_cuil'],
                    'faculty'  => $data['teacher_faculty'],
                ]);
            }
            // Secretaria no tiene campos propios que actualizar

            DB::commit();
            return redirect()->route('adminUsers.index')->with('success', 'Usuario actualizado.');
            
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['general' => 'Error: ' . $e->getMessage()])->withInput(); 
        }
    }

    public function show(User $user)
    {
        // CORRECCIÓN: 'roles' en plural
        $user->loadMissing(['roles', 'teacher', 'secretary']);
        $warning = null; 
        return view('adminUsers.show', compact('user', 'warning'));
    }

    public function destroy(User $user)
    {
        if (auth()->check() && auth()->id() === $user->id) {
            return back()->with('error', 'No podés eliminarte a vos mismo.');
        }

        // CORRECCIÓN: Verificar rol con Spatie (hasRole)
        if ($user->hasRole('admin')) {
            // Contamos cuántos admins quedan usando el scope de Spatie
            $remaining = User::role('admin')->where('id', '!=', $user->id)->count();

            if ($remaining === 0) {
                return back()->with('error', 'No se puede eliminar al último administrador.');
            }
        }

        DB::beginTransaction();
        try {
            // Borrar perfiles asociados
            if ($user->teacher) $user->teacher()->delete();
            if ($user->secretary) $user->secretary()->delete();

            // Borrar usuario (esto borra automáticamente las relaciones en model_has_roles)
            $user->delete();

            DB::commit();
            return back()->with('success', 'Usuario eliminado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}