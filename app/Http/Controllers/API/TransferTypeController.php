<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransferTypeController extends Controller
{
  function __construct()
  {
    $this->middleware('permission:user_income.create|user_expense.create|transfer_type.read', ['only' => ['index']]);
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $data = [];

    $user = Auth::user();

    if($user->can('user_income.create') || $user->can('transfer_type.read')) {
      array_push($data, DB::table('transfer_types')->where('name', '=', 'income')->first());
    }

    if($user->can('user_expense.create') || $user->can('transfer_type.read')) {
      array_push($data, DB::table('transfer_types')->where('name', '=', 'expense')->first());
    }
    return response($data);
  }
}
