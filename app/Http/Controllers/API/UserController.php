<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    // TODO add privileges check
    if (true) {
      $users = DB::table('users')->get(['name', 'email', 'id']);

      return response($users, 200);
    }

    return response('Access deined.', 403);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // TODO add privileges check
    if (true) {
      $validatedData = $request->validate([
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|string|min:8',
        'name' => 'required|string'
      ]);

      $validatedData['password'] = Hash::make($validatedData['password']);

      $user = User::create($validatedData);

      if ($user != null) {
        return response($user, 200);
      }
      return response('Something went wrong.', 500);
    }

    return response('Access deined.', 403);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    // TODO add privileges check
    if (true) {
      return response($user, 200);
    }

    return response('Access deined.', 403);
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
    // TODO add privileges check
    if (true) {
      $validatedData = $request->validate([
        'email' => 'nullable|email|unique:users,email',
        'password' => 'nullable|confirmed|string|min:8',
        'name' => 'nullable|string'
      ]);

      $validatedData['password'] = Hash::make($validatedData['password']);

      if ($user->update($validatedData)) {
        return response($user, 200);
      }

      return response('Something went wrong.', 500);
    }

    return response('Access deined.', 403);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    // TODO add privileges check
    if (true) {


      if ($user->delete()) {
        return response($user, 200);
      }

      return response('Something went wrong.', 500);
    }

    return response('Access deined.', 403);
  }
}
