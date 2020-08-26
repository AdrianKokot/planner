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

    Permission::create(['name' => 'user.read']);
    Permission::create(['name' => 'user.create']);
    Permission::create(['name' => 'user.update']);
    Permission::create(['name' => 'user.delete']);
    Permission::create(['name' => 'log.read']);
    // Permission::create(['name' => 'log.create']);
    // Permission::create(['name' => 'log.update']);
    // Permission::create(['name' => 'log.delete']);
    Permission::create(['name' => 'user_event.read']);
    Permission::create(['name' => 'user_event.create']);
    Permission::create(['name' => 'user_event.update']);
    Permission::create(['name' => 'user_event.delete']);
    Permission::create(['name' => 'role.read']);
    Permission::create(['name' => 'role.create']);
    Permission::create(['name' => 'role.update']);
    Permission::create(['name' => 'role.delete']);
    Permission::create(['name' => 'permission.read']);
    // Permission::create(['name' => 'permission.create']);
    // Permission::create(['name' => 'permission.update']);
    // Permission::create(['name' => 'permission.delete']);
    Permission::create(['name' => 'user_income.read']);
    Permission::create(['name' => 'user_income.create']);
    Permission::create(['name' => 'user_income.update']);
    Permission::create(['name' => 'user_income.delete']);
    Permission::create(['name' => 'user_expense.read']);
    Permission::create(['name' => 'user_expense.create']);
    Permission::create(['name' => 'user_expense.update']);
    Permission::create(['name' => 'user_expense.delete']);

    $role = Role::create(['name' => 'User']);

    $role->givePermissionTo('user_event.read');
    $role->givePermissionTo('user_event.create');
    $role->givePermissionTo('user_event.update');
    $role->givePermissionTo('user_event.delete');
    $role->givePermissionTo('user_income.read');
    $role->givePermissionTo('user_income.create');
    $role->givePermissionTo('user_income.update');
    $role->givePermissionTo('user_income.delete');
    $role->givePermissionTo('user_expense.read');
    $role->givePermissionTo('user_expense.create');
    $role->givePermissionTo('user_expense.update');
    $role->givePermissionTo('user_expense.delete');

    $user = Factory(App\User::class)->create([
      'name' => 'Example User',
      'email' => 'user@planner.com',
      'password' => Hash::make('User123')
    ]);
    $user->assignRole($role);

    $role = Role::create(['name' => 'Administrator']);
    $role->givePermissionTo('log.read');
    $role->givePermissionTo('role.read');
    $role->givePermissionTo('role.create');
    $role->givePermissionTo('role.update');
    $role->givePermissionTo('role.delete');
    $role->givePermissionTo('permission.read');
    $role->givePermissionTo('user.read');
    $role->givePermissionTo('user.create');
    $role->givePermissionTo('user.update');
    $role->givePermissionTo('user.delete');

    $user = Factory(App\User::class)->create([
      'name' => 'Example Admin',
      'email' => 'admin@planner.com',
      'password' => Hash::make('Admin123')
    ]);
    $user->assignRole($role);
  }
}
