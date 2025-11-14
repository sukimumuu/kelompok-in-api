<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permission = [
            'login',
            'add student',
            'delete student',
            'delete teacher',
            'assign leader',
            'unassign leader',
            'create class',
            'delete class',
            'update class'
        ];

        foreach ($permission as $perm) {
            Permission::create(['name' => $perm]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::create(['name' => 'student']);
        $role->givePermissionTo(['login']);

        $role = Role::create(['name' => 'leader']);
        $role->givePermissionTo(['login', 'delete student']);

        $role = Role::create(['name' => 'teacher']);
        $role->givePermissionTo(['login' ,'delete student', 'assign leader', 'unassign leader', 'create class', 'delete class', 'update class']);
        
        $role = Role::create(['name' => 'superadmin']);
        $role->givePermissionTo(Permission::all());
    }
}
