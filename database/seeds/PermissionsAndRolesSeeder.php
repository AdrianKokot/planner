<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsAndRolesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::create(['name' => 'user.*']);
    Permission::create(['name' => 'log.*']);
    Permission::create(['name' => 'user_event.*']);
    Permission::create(['name' => 'role.*']);
    Permission::create(['name' => 'permission.*']);
    Permission::create(['name' => 'user_income.*']);
    Permission::create(['name' => 'user_expense.*']);

    $role = Role::create(['name' => 'user']);

    $role->givePermissionTo('user_event.*');
    $role->givePermissionTo('user_income.*');
    $role->givePermissionTo('user_expense.*');

    $user = Factory(App\User::class)->create([
      'name' => 'Example User',
      'email' => 'user@bbraun.com',
      'password' => Hash::make('User123')
    ]);
    $user->assignRole($role);

    $role = Role::create(['name' => 'administrator']);
    $role->givePermissionTo('log.*');
    $role->givePermissionTo('role.*');
    $role->givePermissionTo('permission.*');
    $role->givePermissionTo('user.*');

    $user = Factory(App\User::class)->create([
      'name' => 'Example Admin',
      'email' => 'admin@bbraun.com',
      'password' => Hash::make('Admin123')
    ]);
    $user->assignRole($role);
  }
}
