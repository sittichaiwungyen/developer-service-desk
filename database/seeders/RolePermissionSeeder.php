<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $roles = ['Admin','Manager','Developer','Support','Viewer'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Define some basic permissions (expand as needed)
        $permissions = [
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.delete',
            'projects.view', 'projects.manage',
            'worklogs.manage',
            'knowledge.manage'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign all permissions to Admin
        $admin = Role::where('name','Admin')->first();
        if ($admin) {
            $admin->syncPermissions($permissions);
        }
    }
}
