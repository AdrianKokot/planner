<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

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
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  Role $role
   * @return \Illuminate\Http\Response
   */
  public function show($role)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Role $role
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $role)
  {
    //
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
      return response($role, 200);
    }

    return response(['message' => 'Something went wrong.'], 500);
  }
}
