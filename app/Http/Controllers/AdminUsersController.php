<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Secretary;
use App\Models\Teacher;
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

    public function index()
    {
        // Obtener todos los usuarios con sus roles y datos relacionados
     $users = User::select('id', 'email', 'role_id')
    ->with([
        'role:id,name',
        'teacher:id,user_id,name,lastname,dni,cuil,faculty',
        'secretary:id,user_id,username'
    ])
    ->leftJoin('teachers', 'users.id', '=', 'teachers.user_id')
    ->orderBy('teachers.name', 'asc')
    ->select('users.*') // evita conflicto de columnas
    ->get();

        return view('adminUsers.index', compact('users'));
    }

     public function create()
    {
        // Obtener todos los roles para el <select> en el formulario
        $roles = Role::all(); 
    
        return view('adminUsers.create', compact('roles'));
    }

// Maneja la creación del usuario y su perfil asociado (Secretary o Teacher)
public function store(Request $request)
    {
        // --- 1. Obtener IDs de Roles por Nombre (Flexible)
        $secretaryRole = Role::where('name', 'secretary')->first();
        $teacherRole = Role::where('name', 'teacher')->first();

        if (!$secretaryRole || !$teacherRole) {
            return redirect()->back()->withInput()
                ->with('error', 'Error de configuración: Roles "secretary" o "teacher" no encontrados.');
        }

        $SECRETARY_ROLE_ID = $secretaryRole->id;
        $TEACHER_ROLE_ID = $teacherRole->id;
        
        // --- 2. Validaciones Condicionales
        $rules = [
            'name'      => ['required', 'string', 'max:40', 'unique:secretaries,username'], 
            'email'     => ['required', 'string', 'email', 'max:30', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'role_id'   => ['required', 'integer', 'in:' . $SECRETARY_ROLE_ID . ',' . $TEACHER_ROLE_ID], 

            // Teacher fields (Conditionally required)
            'docente_nombre' => [Rule::requiredIf(fn () => $request->role_id == $TEACHER_ROLE_ID), 'nullable', 'string', 'max:40'],
            'docente_apellido' => [Rule::requiredIf(fn () => $request->role_id == $TEACHER_ROLE_ID), 'nullable', 'string', 'max:40'],
            'dni' => [Rule::requiredIf(fn () => $request->role_id == $TEACHER_ROLE_ID), 'nullable', 'integer', 'unique:teachers,dni'],
            'cuil' => ['nullable', 'string', 'max:20', 'unique:teachers,cuil'],
            'facultad' => ['nullable', 'string', 'max:20'],
            'is_rector' => ['nullable', 'boolean'],
            'is_dean' => ['nullable', 'boolean'],
        ];

        $validated = $request->validate($rules);


        // --- 3. Transacción de Base de Datos (Usando el namespace completo)
        
        // El uso de una transacción es OBLIGATORIO para inserciones multi-tabla.
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // 3.1. Crear el registro principal en la tabla 'users'
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
            ]);
            
            // 3.2. Crear el registro en la tabla asociada
            if ($validated['role_id'] == $SECRETARY_ROLE_ID) {
                
                // Lógica para Secretaria
                Secretary::create([
                    'user_id' => $user->id,
                    'username' => $validated['name'], 
                ]);
                
            } elseif ($validated['role_id'] == $TEACHER_ROLE_ID) {
                
                // Lógica para Docente
                Teacher::create([
                    'user_id' => $user->id,
                    'name' => $validated['docente_nombre'],
                    'lastname' => $validated['docente_apellido'],
                    'dni' => $validated['dni'],
                    'cuil' => $validated['cuil'],
                    'faculty' => $validated['facultad'],
                    'is_rector' => $request->boolean('is_rector'),
                    'is_dean' => $request->boolean('is_dean'),
                ]);
            }
            
            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('adminUsers.index') 
                             ->with('success', 'Usuario y perfil asociado creado con éxito.');

        } catch (\Exception $e) {
            
            \Illuminate\Support\Facades\DB::rollBack();
            
          // Muestra el mensaje de error de la base de datos o validación.
            dd($e->getMessage()); 
            
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error al crear el usuario. Revise los datos e intente de nuevo.');
        }
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
