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

        Permission::create(['name' => 'login']);
        Permission::create(['name' => 'delete student']);
        Permission::create(['name' => 'delete leader']);
        Permission::create(['name' => 'delete teacher']);
        Permission::create(['name' => 'assign leader']);


        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::create(['name' => 'student']);
        $role->givePermissionTo(['login']);

        $role = Role::create(['name' => 'leader']);
        $role->givePermissionTo(['login', 'delete student']);

        $role = Role::create(['name' => 'teacher']);
        $role->givePermissionTo(['login', 'delete leader' ,'delete student', 'assign leader']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(['login','delete student', 'delete teacher']);
        
        $role = Role::create(['name' => 'superadmin']);
        $role->givePermissionTo(Permission::all());
    }
}
