<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use DB;
use Hash;
use Illuminate\Validation\Rule;
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

    public function edit(User $user)
    {

        return view('adminUsers.edit', compact('user'));
    }



    public function update(Request $request, User $user)
    {

        // Normalizar email
        $normalizedEmail = strtolower(trim($request->input('email', '')));

        $rules = [
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id, 'id')],
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
        ];

        if ($request->filled('new_password') || $request->filled('old_password') || $request->filled('new_password_confirmation')) {
            $rules['old_password'] = ['required'];
            $rules['new_password'] = ['required', 'min:8', 'confirmed'];
        }

        // Reemplazo en el request para validar con el email normalizado
        $request->merge(['email' => $normalizedEmail]);

        $data = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Comprobación adicional por si hay conflicto (útil para depurar)
            $conflict = User::where('email', $normalizedEmail)
                ->where('id', '!=', $user->id)
                ->first();

            if ($conflict) {
                return back()->withErrors(['email' => 'El email ya está en uso por otro usuario (id: ' . $conflict->id . ')'])->withInput();
            }

            // Cambio de contraseña: validar old_password
            if (!empty($data['new_password'])) {
                if (!Hash::check($data['old_password'] ?? '', $user->password)) {
                    return back()->withErrors(['old_password' => 'La contraseña actual es incorrecta'])->withInput();
                }
                $user->password = Hash::make($data['new_password']);
            }

            // Actualizar campos básicos
            $user->name = $data['name'] ?? $user->name;
            $user->email = $normalizedEmail;
            $user->save();

            // Recargar relaciones para trabajar con datos actualizados
            $user->refresh();
            $user->load('role', 'secretary', 'teacher');

            $roleName = optional($user->role)->name;

            // Si la columna real en la tabla secretaries es user_name (según tu diagrama),
            // mapeamos el input 'username' a 'user_name'. Cambiar si tu columna se llama 'username'.
            $secColumn = 'user_name'; // <-- ajustar si la columna real es 'username'

            // --- Si ahora es secretary: crear/actualizar secretary y eliminar teacher si existiera ---
            if ($roleName === 'secretary') {
                $sec = $user->secretary; // puede ser null

                $secData = [
                    $secColumn => $data['username'] ?? optional($sec)->{$secColumn} ?? null,
                ];

                if ($sec) {
                    $sec->update($secData);
                } else {
                    $secData['user_id'] = $user->id;
                    \App\Models\Secretary::create($secData);
                }

                // Opcional: eliminar teacher si existía (para mantener consistencia 1 relación por rol)
                if ($user->teacher) {
                    $user->teacher()->delete();
                }
            }

            // --- Si ahora es teacher: crear/actualizar teacher y eliminar secretary si existiera ---
            if ($roleName === 'teacher') {
                $teach = $user->teacher;

                $teachData = [
                    'name' => $data['name'] ?? optional($teach)->name ?? null,
                    'lastname' => $data['lastname'] ?? optional($teach)->lastname ?? null,
                ];

                if ($teach) {
                    $teach->update($teachData);
                } else {
                    $teachData['user_id'] = $user->id;
                    \App\Models\Teacher::create($teachData);
                }

                // Eliminamos secretary previa si existe
                if ($user->secretary) {
                    $user->secretary()->delete();
                }
            }

            DB::commit();

            return redirect()->route('adminUsers.index')->with('success', 'Usuario actualizado correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();
            // Temporal: mostrar error real para depuración
            dd($e->getMessage(), $e->getTraceAsString());

            // En producción preferible:
            // \Log::error('Error update user: '.$e->getMessage());
            // return back()->withErrors(['general' => 'Ocurrió un error al actualizar el usuario'])->withInput();
        }
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
