<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@redtech.co.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('redtech.co.id'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: admin@retech.co.id / redtech.co.id');



        // Create Basic Permissions first
        $now = now();
        $permissions = [
            [
                'display_name' => 'View Users', // Custom display name
                'name' => 'view-users',         // Spatie name (slug)
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create User',
                'name' => 'create-user',
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit User',
                'name' => 'edit-user',
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete User',
                'name' => 'delete-user',
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Roles',
                'name' => 'view-roles',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Role',
                'name' => 'create-role',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Role',
                'name' => 'edit-role',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Role',
                'name' => 'delete-role',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Permissions',
                'name' => 'view-permissions',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Permission',
                'name' => 'create-permission',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Permission',
                'name' => 'edit-permission',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Permission',
                'name' => 'delete-permission',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Activity Logs',
                'name' => 'view-activity-logs',
                'description' => null,
                'module' => 'activity_logs',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'display_name' => 'View Laravel Logs',
                'name' => 'view-laravel-logs',
                'description' => 'Access to view Laravel application logs',
                'module' => 'logs',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Settings',
                'name' => 'view-settings',
                'description' => null,
                'module' => 'settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Setting',
                'name' => 'edit-setting',
                'description' => null,
                'module' => 'settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Catalog Permissions
            [
                'display_name' => 'View Stores',
                'name' => 'view-stores',
                'description' => null,
                'module' => 'stores',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Store',
                'name' => 'create-stores',
                'description' => null,
                'module' => 'stores',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Store',
                'name' => 'edit-stores',
                'description' => null,
                'module' => 'stores',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Store',
                'name' => 'delete-stores',
                'description' => null,
                'module' => 'stores',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Categories',
                'name' => 'view-categories',
                'description' => null,
                'module' => 'categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Category',
                'name' => 'create-categories',
                'description' => null,
                'module' => 'categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Category',
                'name' => 'edit-categories',
                'description' => null,
                'module' => 'categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Category',
                'name' => 'delete-categories',
                'description' => null,
                'module' => 'categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Products',
                'name' => 'view-products',
                'description' => null,
                'module' => 'products',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Product',
                'name' => 'create-products',
                'description' => null,
                'module' => 'products',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Product',
                'name' => 'edit-products',
                'description' => null,
                'module' => 'products',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Product',
                'name' => 'delete-products',
                'description' => null,
                'module' => 'products',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                $permission
            );
        }

        $this->command->info('System permissions created');



        // Create Administrator Role
        $administratorRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            [
                'display_name' => 'Administrator',
                'name' => 'admin',
                'description' => 'Full system access',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('Administrator role created');

        // Assign all permissions to Administrator role
        $allPermissions = Permission::all();
        if ($allPermissions->count() > 0) {
            $administratorRole->syncPermissions($allPermissions);
            $this->command->info('All permissions assigned to Administrator role');
        }

        // Assign Administrator role to admin user
        if ($administratorRole) {
            $admin->assignRole($administratorRole);
            $this->command->info('Admin user assigned to Administrator role');
        }

        // Create Developer Role
        $developerRole = Role::updateOrCreate(
            ['name' => 'developer', 'guard_name' => 'web'],
            [
                'display_name' => 'Developer',
                'name' => 'developer',
                'description' => 'Access to view Laravel logs and system debugging',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('Developer role created');

        // Assign Laravel Logs permission to Developer role
        $viewLaravelLogsPermission = Permission::where('name', 'view-laravel-logs')->first();
        if ($viewLaravelLogsPermission) {
            $developerRole->givePermissionTo($viewLaravelLogsPermission);
            $this->command->info('Laravel Logs permission assigned to Developer role');
        }

        // Create Admin Toko Role
        $adminTokoRole = Role::updateOrCreate(
            ['name' => 'admin-toko', 'guard_name' => 'web'],
            [
                'display_name' => 'Admin Toko',
                'name' => 'admin-toko',
                'description' => 'Akses kelola katalog tenant',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('Admin Toko role created');

        $adminTokoPermissions = Permission::whereIn('name', [
            'view-stores',
            'create-stores',
            'edit-stores',
            'delete-stores',
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',
            'view-products',
            'create-products',
            'edit-products',
            'delete-products'
        ])->get();

        if ($adminTokoPermissions->count() > 0) {
            $adminTokoRole->syncPermissions($adminTokoPermissions);
            $this->command->info('E-Katalog permissions assigned to Admin Toko role');
        }
    }
}
