<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Limpiar la caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // =========================================================================
        // 2. DEFINICIÓN DE PERMISOS (GRANULARES)
        // =========================================================================

        // --- A. USUARIOS ---
        $verUsuarios  = Permission::firstOrCreate(['name' => 'ver usuarios', 'guard_name' => 'web']);
        $crudUsuarios = Permission::firstOrCreate(['name' => 'crud usuarios', 'guard_name' => 'web']);

        // --- B. DOCENTES ---
        $verDocentes  = Permission::firstOrCreate(['name' => 'ver docentes', 'guard_name' => 'web']);
        $crudDocentes = Permission::firstOrCreate(['name' => 'crud docentes', 'guard_name' => 'web']);

        // --- C. ALUMNOS ---
        $verAlumnos  = Permission::firstOrCreate(['name' => 'ver alumnos', 'guard_name' => 'web']);
        $crudAlumnos = Permission::firstOrCreate(['name' => 'crud alumnos', 'guard_name' => 'web']);

        // --- D. EMPRESAS ---
        $verEmpresas  = Permission::firstOrCreate(['name' => 'ver empresas', 'guard_name' => 'web']);
        $crudEmpresas = Permission::firstOrCreate(['name' => 'crud empresas', 'guard_name' => 'web']);

        // --- E. CONVENIOS (Gestión general) ---
        $verConvenios  = Permission::firstOrCreate(['name' => 'ver convenios', 'guard_name' => 'web']);
        $crudConvenios = Permission::firstOrCreate(['name' => 'crud convenios', 'guard_name' => 'web']);

        // --- F. CARRERAS ---
        $verCarreras  = Permission::firstOrCreate(['name' => 'ver carreras', 'guard_name' => 'web']);
        $crudCarreras = Permission::firstOrCreate(['name' => 'crud carreras', 'guard_name' => 'web']);

        // --- G. DEPARTAMENTOS ---
        $verDepartamentos  = Permission::firstOrCreate(['name' => 'ver departamentos', 'guard_name' => 'web']);
        $crudDepartamentos = Permission::firstOrCreate(['name' => 'crud departamentos', 'guard_name' => 'web']);

        // --- H. SOLICITUDES (NUEVO) ---
        $verSolicitudes  = Permission::firstOrCreate(['name' => 'ver solicitudes', 'guard_name' => 'web']);
        $crudSolicitudes = Permission::firstOrCreate(['name' => 'crud solicitudes', 'guard_name' => 'web']);

        // --- I. APROBACIONES (Flujos específicos) ---
        $aprobarConveniosGeneral      = Permission::firstOrCreate(['name' => 'aprobar convenios general', 'guard_name' => 'web']); // Secretaria
        $aprobarConveniosEspecificos  = Permission::firstOrCreate(['name' => 'aprobar convenios especificos', 'guard_name' => 'web']); // Director
        $aprobarConveniosIndividuales = Permission::firstOrCreate(['name' => 'aprobar convenios individuales', 'guard_name' => 'web']); // Coordinador

        // --- J. PERMISOS ESPECIALES DOCENTE (Limitados/Propios) ---
        $crearSolicitudConvenio      = Permission::firstOrCreate(['name' => 'crear solicitud convenio', 'guard_name' => 'web']);
        $descargarConvenioVinculado  = Permission::firstOrCreate(['name' => 'descargar convenio vinculado', 'guard_name' => 'web']);
        $verMotivoRechazo            = Permission::firstOrCreate(['name' => 'ver motivo rechazo', 'guard_name' => 'web']);
        $verRechazadas               = Permission::firstOrCreate(['name' => 'ver rechazadas', 'guard_name' => 'web']);


        // =========================================================================
        // 3. CREACIÓN DE ROLES
        // =========================================================================
        $admin       = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $secretaria  = Role::firstOrCreate(['name' => 'Secretaria', 'guard_name' => 'web']);
        $director    = Role::firstOrCreate(['name' => 'Director', 'guard_name' => 'web']); 
        $coordinador = Role::firstOrCreate(['name' => 'Coordinador', 'guard_name' => 'web']);
        $docente     = Role::firstOrCreate(['name' => 'Docente', 'guard_name' => 'web']);


        // =========================================================================
        // 4. ASIGNACIÓN DE PERMISOS
        // =========================================================================

        // --- AGRUPACIÓN DE VISTAS (Para Director/Coordinador que ven todo) ---
        // AGREGADO: $verSolicitudes
        $verTodo = [
            $verUsuarios, $verDocentes, $verAlumnos, 
            $verEmpresas, $verConvenios, $verCarreras, 
            $verDepartamentos, $verSolicitudes, $verRechazadas
        ];

        $todoMenosUsuarios = [
            $verDocentes, $verAlumnos, 
            $verEmpresas, $verConvenios, $verCarreras, 
            $verDepartamentos, $verSolicitudes, $verRechazadas
        ];

        // --- AGRUPACIÓN DE CRUD TOTAL (Para Admin/Secretaria) ---
        // AGREGADO: $crudSolicitudes
        $crudTodo = [
            $crudUsuarios, $crudDocentes, $crudAlumnos, 
            $crudEmpresas, $crudConvenios, $crudCarreras, 
            $crudDepartamentos, $crudSolicitudes
        ];


        // 1) ADMIN
        $admin->syncPermissions(array_merge($verTodo, $crudTodo));


        // 2) SECRETARIA
        // Sumamos el poder de aprobación
        $permisosSecretaria = array_merge($verTodo, $crudTodo, [$aprobarConveniosGeneral]);
        $secretaria->syncPermissions($permisosSecretaria);


        // 3) DIRECTOR
        // Ve todo, CRUD Docentes, Aprueba Específicos
        $permisosDirector = array_merge($todoMenosUsuarios, [
            $crudDocentes, 
            $aprobarConveniosEspecificos
        ]);
        $director->syncPermissions($permisosDirector);


        // 4) COORDINADOR
        // Ve todo, CRUD Docentes, Aprueba Individuales
        $permisosCoordinador = array_merge($todoMenosUsuarios, [
            $crudDocentes, 
            $aprobarConveniosIndividuales
        ]);
        $coordinador->syncPermissions($permisosCoordinador);


        // 5) DOCENTE
        $permisosDocente = [
            $verConvenios, 
            $verSolicitudes, // AGREGADO: Necesita ver las que creó
            $crearSolicitudConvenio,
            $verEmpresas,  
            $crudEmpresas, 
            $crudAlumnos, 
            $verAlumnos,
            $descargarConvenioVinculado,
            $verMotivoRechazo,
            $verRechazadas,
            $verDocentes
        ];
        $docente->syncPermissions($permisosDocente);
    }
}