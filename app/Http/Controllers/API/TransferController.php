<?php

namespace App\Http\Controllers\API;

use App\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
  function __construct()
  {
    $this->middleware('permission:user_income.read|user_expense.read', ['only' => ['index', 'show']]);
    $this->middleware('permission:user_income.read|user_expense.read', ['only' => ['store']]);
    $this->middleware('permission:user_income.read|user_expense.read', ['only' => ['update']]);
    $this->middleware('permission:user_income.read|user_expense.read', ['only' => ['destroy']]);
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $transferType = $request->query('type') ?? 'both';

    $user = Auth::user();

    $canAccess = $user->can('user_income.read') ? ($user->can('user_expense.read') ? true : ($transferType == 'income')) : ($user->can('user_expense.read') ? $transferType == 'expense' : false);

    $transferTypeId = DB::table('transfer_types')->where('name', '=', $transferType);

    if ($canAccess) {

      $transfers = [];

      if ($transferType == 'both') {
        $transfers = DB::table('transfers')->get();
      } else {
        $transfers = DB::table('transfers')->where('transfer_type_id', '=', DB::table('transfer_types')->where('name', '=', $transferType)->first()->name)->get();
      }

      return response($transfers);
    }

    abort(403, 'Forbidden');
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
   * @param  \App\Transfer  $transfer
   * @return \Illuminate\Http\Response
   */
  public function show(Transfer $transfer)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Transfer  $transfer
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Transfer $transfer)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Transfer  $transfer
   * @return \Illuminate\Http\Response
   */
  public function destroy(Transfer $transfer)
  {
    //
  }
}
