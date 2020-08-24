<?php

namespace App\Http\Controllers\API;

use App\Log;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
  function __construct()
  {
    $this->middleware('permission:user.read', ['only' => ['index', 'show']]);
    $this->middleware('permission:user.create', ['only' => ['store']]);
    $this->middleware('permission:user.update', ['only' => ['update']]);
    $this->middleware('permission:user.delete', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $users = User::with('roles')->get();
    return response($users, 200);
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
      'email' => 'required|email|unique:users,email',
      'password' => 'required|confirmed|string|min:8',
      'name' => 'required|string'
    ]);

    $validatedData['password'] = Hash::make($validatedData['password']);

    $user = User::create($validatedData);

    if ($user != null) {
      Log::log(Auth::user(), $user, 'user', 'create', $validatedData);
      return response($user, 200);
    }
    return response(['message' => 'Something went wrong.'], 500);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    return response($user, 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, User $user)
  {
    $validatedData = $request->validate([
      'email' => 'nullable|email|unique:users,email,' . $user->id,
      'name' => 'nullable|string',
      'role' => 'nullable|numeric',
      'password' => 'nullable|string|min:8|max:32|confirmed'
    ]);

    foreach($validatedData as $key => $value) {
      if(empty($value)) {
        unset($validatedData[$key]);
      }
    }

    $oldUser = clone $user;

    if(!empty($validatedData['role'])) {
      $role = DB::table('roles')->where('id', '=', $validatedData['role'])->first();
      unset($validatedData['role']);
      $oldUser->role = $user->roles()->first()->name;
    }

    if(!empty($validatedData['password'])) {
      $validatedData['password'] = Hash::make($validatedData['password']);
    }

    if ($user->update($validatedData)) {

      $validatedData['role'] = $role->name;
      $user->syncRoles([$role->name]);

      Log::log(Auth::user(), $oldUser, 'user', 'update', $validatedData);
      return response($user, 200);
    }

    return response(['message' => 'Something went wrong.'], 500);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    if ($user->delete()) {
      Log::log(Auth::user(), $user, 'user', 'delete');
      return response($user, 200);
    }

    return response(['message' => 'Something went wrong.'], 500);
  }
}
