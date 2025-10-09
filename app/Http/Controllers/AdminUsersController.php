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
use Schema;

class AdminUsersController extends Controller
{

    protected $serviceUsers;

    public function __construct(UserService $serviceUsers)
    {
        $this->serviceUsers = $serviceUsers;
    }

   

public function index()
{
    
    $users = User::select('id', 'email', 'name', 'role_id')
        ->with([
            'role:id,name',
            'teacher:id,user_id,name', 
            'secretary:id,user_id,username' 
        ])
        
        ->orderBy('name', 'asc') // Ordenar por el nombre de la tabla 'users'
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



    public function edit(User $user)
    {

        return view('adminUsers.edit', compact('user'));
    }



  
public function update(Request $request, User $user)
{
    // --- 1. Definición y Normalización de Datos ---

    // Normalizar email
    $normalizedEmail = strtolower(trim($request->input('email', '')));
    $roleName = optional($user->role)->name;
    
    // El campo para la columna 'user_name' en la tabla secretary
    $secColumn = 'username'; // Ajustar si la columna real es 'user_name'
    
    // Reglas base
    $rules = [
        'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        'name' => ['required', 'string', 'max:255'], // Campo 'name' del modelo User
        
        // Campos de contraseña (opcionales)
        'new_password' => ['nullable', 'min:8', 'confirmed'],
        'old_password' => ['nullable'], // Solo necesario si el propio usuario se edita, como Admin, no necesitamos validarlo, a menos que sea un requerimiento de tu negocio.
    ];

    // --- 2. Reglas Condicionales por Rol ---

    if ($roleName === 'teacher') {
        $rules['teacher_name'] = ['required', 'string', 'max:255'];
        $rules['teacher_lastname'] = ['required', 'string', 'max:255'];
        $rules['teacher_dni'] = ['nullable', 'string', 'max:50'];
        $rules['teacher_cuil'] = ['nullable', 'string', 'max:50'];
        $rules['teacher_faculty'] = ['nullable', 'string', 'max:255'];
    } elseif ($roleName === 'secretary') {
        $rules['secretary_username'] = ['required', 'string', 'max:255'];
    }

    // Si se proporciona new_password, old_password ya no es estrictamente requerido para el admin,
    // pero si lo dejas en el formulario, es buena práctica validarlo solo si el usuario
    // lo completó. Para edición de admin, quitamos la lógica de verificación de password.
    // Si necesitas validar old_password, descomenta la siguiente lógica:
    /*
    if ($request->filled('new_password')) {
        $rules['old_password'] = ['required']; // Si REQUIERES la clave actual para cualquier cambio
    }
    */
    
    // Reemplazo en el request para validar con el email normalizado
    $request->merge(['email' => $normalizedEmail]);
    
    $data = $request->validate($rules);

    // --- 3. Ejecución de Actualización ---

    DB::beginTransaction();
    try {
        // --- 3.1 Actualizar campos base del usuario ---
        
        $updateData = [
            'name' => $data['name'],
            'email' => $normalizedEmail,
        ];
        
        // --- 3.2 Actualizar contraseña (Admin no necesita old_password) ---
        if ($request->filled('new_password')) {
            // Nota: Aquí se asume que un administrador está realizando la edición
            // y no necesita la contraseña anterior para restablecerla.
            $updateData['password'] = Hash::make($data['new_password']);
        }
        
        $user->update($updateData);

        // --- 3.3 Actualizar relaciones (Teacher / Secretary) ---
        if ($roleName === 'teacher' && $user->teacher) {
            $user->teacher->update([
                'name' => $data['teacher_name'],
                'lastname' => $data['teacher_lastname'],
                'dni' => $data['teacher_dni'],
                'cuil' => $data['teacher_cuil'],
                'faculty' => $data['teacher_faculty'],
            ]);
        } elseif ($roleName === 'secretary' && $user->secretary) {
            // Se asume que el input 'secretary_username' se mapea a la columna '$secColumn' (username o user_name)
            $user->secretary->update([
                $secColumn => $data['secretary_username'],
            ]);
        }

        // --- 3.4 Manejo de Roles (Opcional) ---
        // Si tienes lógica para actualizar las tablas related incluso si la relación
        // no existe (ej. el rol se acaba de asignar en otra parte), puedes usar:
        // User::firstOrCreate(['user_id' => $user->id], $teachData) para crear si no existe.
        // Pero dado tu formulario, solo actualizamos si la relación existe.

        DB::commit();

        return redirect()->route('adminUsers.show', $user)->with('success', 'Usuario y datos relacionados actualizados correctamente. ✅');
        
    } catch (\Throwable $e) {
        DB::rollBack();
        
        // Loggear el error para depuración
        \Log::error('Error al actualizar el usuario (ID: ' . $user->id . '): ' . $e->getMessage());

        // Mostrar un error genérico en producción
        // return back()->withErrors(['general' => 'Ocurrió un error al actualizar el usuario: ' . $e->getMessage()])->withInput(); 

        // Mostrar un error detallado para desarrollo
        return back()->withErrors(['general' => 'Ocurrió un error al actualizar el usuario: ' . $e->getMessage()])->withInput(); 
    }
}







    public function show(User $user)
    {
      // Carga las relaciones 'role', 'teacher' y 'secretary' de manera eficiente.
    $user->loadMissing(['role', 'teacher', 'secretary']);

    // La variable $warning es opcional, puedes quitarla si no la necesitas.
    // La vista ahora maneja todos los roles internamente.
    $warning = null; 
    
    return view('adminUsers.show', compact('user', 'warning'));
    }


    public function destroy(User $user)
    {
     
        // No permitir que te borres a vos mismo
        if (auth()->check() && auth()->id() === $user->id) {
            return back()->with('error', 'No podés eliminarte a vos mismo.');
        }

        // Evitar borrar al último admin (ajustá el nombre del rol si corresponde)
        if ($user->role?->name === 'admin') {
            $remaining = User::whereHas('role', function ($q) {
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
            \Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al intentar eliminar el usuario.');
        }
    }
}
