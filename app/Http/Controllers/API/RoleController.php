<?php

namespace App\Http\Controllers\API;

use App\Log;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
  function __construct()
  {
    $this->middleware('permission:role.read', ['only' => ['index', 'show']]);
    $this->middleware('permission:role.create', ['only' => ['store']]);
    $this->middleware('permission:role.update', ['only' => ['update']]);
    $this->middleware('permission:role.delete', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return response(Role::all(['id', 'name']));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'permissions' => 'required|array',
      'permissions.*' => 'nullable|string|max:255'
    ]);

    $role = Role::create([
      'name' => $validatedData['name'],
      'guard_name' => 'web'
    ]);

    $role->syncPermissions($validatedData['permissions']);

    if($role->save()) {
      Log::log(Auth::user(), $role, 'role', 'create', $validatedData);
      return response($role);
    }
    return response(['message' => 'Something went wrong.'], 500);
  }

  /**
   * Display the specified resource.
   *
   * @param  Role $role
   * @return \Illuminate\Http\Response
   */
  public function show(Role $role)
  {
    $role->getAllPermissions();
    return response($role);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Role $role
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Role $role)
  {
    $validatedData = $request->validate([
      'name' => 'nullable|string',
      'permissions' => 'nullable|array',
      'permissions.*' => 'nullable|string'
    ]);

    $role->getPermissionNames();
    $oldRole = clone $role;
    $role->name = $validatedData['name'];
    $role->syncPermissions($validatedData['permissions']);

    if($role->save()) {
      Log::log(Auth::user(), $oldRole, 'role', 'update', $validatedData);
      return response($role);
    }
    return response(['message' => 'Something went wrong.'], 500);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Role $role
   * @return \Illuminate\Http\Response
   */
  public function destroy(Role $role)
  {
    if ($role->delete()) {
      Log::log(Auth::user(), $role, 'role', 'delete');
      return response($role);
    }

    return response(['message' => 'Something went wrong.'], 500);
  }
}
