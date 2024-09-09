<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $AdminRole = Role::create(['name' => 'admin']);

    $ManagerRole = Role::create(['name' => 'manager']);

    $UserRole = Role::create(['name' => 'user']);

    $user = \App\Models\User::factory()->create([
      'name' => 'Admin',
      'email' => 'admin@example.com',
      'password' => '12345678'
    ]);
    $user->assignRole($AdminRole);

    $user = \App\Models\User::factory()->create([
      'name' => 'Manager',
      'email' => 'manager@example.com',
      'password' => '12345678'
    ]);
    $user->assignRole($ManagerRole);

    $user = \App\Models\User::factory()->create([
      'name' => 'User',
      'email' => 'user@example.com',
      'password' => '12345678'
    ]);
    $user->assignRole($UserRole);
  }
}
