<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
  public function run(): void
  {
    // Create basic permissions
    $basicPermissions = [
      'view-profile' => 'View own profile',
      'edit-profile' => 'Edit own profile',
      'delete-profile' => 'Delete own profile',
    ];

    $adminPermissions = [
      'manage-users' => 'Manage all users',
      'manage-roles' => 'Manage roles and permissions',
      'view-logs' => 'View system logs',
    ];

    foreach ($basicPermissions as $slug => $name) {
      Permission::create([
        'name' => $name,
        'slug' => $slug,
        'module' => 'user',
      ]);
    }

    foreach ($adminPermissions as $slug => $name) {
      Permission::create([
        'name' => $name,
        'slug' => $slug,
        'module' => 'admin',
      ]);
    }

    // Create default roles
    $userRole = Role::create([
      'name' => 'User',
      'slug' => 'user',
      'description' => 'Default user role',
      'is_default' => true,
    ]);

    $adminRole = Role::create([
      'name' => 'Administrator',
      'slug' => 'admin',
      'description' => 'System administrator',
      'is_default' => false,
    ]);

    // Assign permissions to roles
    $userRole->permissions()->attach(
      Permission::whereIn('slug', array_keys($basicPermissions))->pluck('id')
    );

    $adminRole->permissions()->attach(
      Permission::pluck('id') // All permissions
    );
  }
}
