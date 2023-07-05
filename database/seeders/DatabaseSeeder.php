<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Scale;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['Administrador', 'Moderador', 'Financeiro 1', 'Financeiro 2'];
        $permissions = ['View', 'Create', 'Update', 'Delete'];
        Scale::factory()->create();

        foreach ($roles as $item) {
            $role = Role::factory()->create([
                'name' => $item
            ]);
            User::factory()->create([
                'nickname' => str_replace(' ', '', strtolower($item)),
                'role_id' => $role->id,
            ]);
        }

        foreach ($permissions as $key => $item) {
            $permission = Permission::factory()->create([
                'name' => $item
            ]);
            if ($key == 0) {
                foreach ($roles as $index => $role) {
                    RolePermission::create([
                        'role_id' => Role::where('name', $role)->first()->id,
                        'permission_id' => $permission->id
                    ]);
                }
            }
            if ($key == 1) {
                foreach ($roles as $index => $role) {
                    if (in_array($index, [0])) {
                        RolePermission::create([
                            'role_id' => Role::where('name', $role)->first()->id,
                            'permission_id' => $permission->id
                        ]);
                    }
                }
            }
            if ($key == 2) {
                foreach ($roles as $index => $role) {
                    if (in_array($index, [0, 2])) {
                        RolePermission::create([
                            'role_id' => Role::where('name', $role)->first()->id,
                            'permission_id' => $permission->id
                        ]);
                    }
                }
            }
            if ($key == 3) {
                foreach ($roles as $index => $role) {
                    if (in_array($index, [0, 3])) {
                        RolePermission::create([
                            'role_id' => Role::where('name', $role)->first()->id,
                            'permission_id' => $permission->id
                        ]);
                    }
                }
            }
        }
    }
}
