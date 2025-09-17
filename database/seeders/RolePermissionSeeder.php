<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $user  = Role::create(['name' => 'user']);

        // Crear permisos
        $editPosts = Permission::create(['name' => 'edit posts']);
        $viewPosts = Permission::create(['name' => 'view posts']);

        // Asignar permisos a roles
        $admin->givePermissionTo([$editPosts, $viewPosts]);
        $user->givePermissionTo($viewPosts);
    }
}
