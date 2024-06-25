<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'manage everything']);
        Permission::create(['name' => 'upload']);
        Permission::create(['name' => 'view evacuation']);

        // create roles and assign existing permissions
        $role = Role::create(['name' => 'Admin']);
        $role->givePermissionTo('manage everything');

        $role = Role::create(['name' => 'Volunteer']);
        $role->givePermissionTo(['upload', 'view evacuation']);
    }
}
