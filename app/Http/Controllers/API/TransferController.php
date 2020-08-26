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
    $this->middleware('permission:user_income.create|user_expense.create', ['only' => ['store']]);
    $this->middleware('permission:user_income.update|user_expense.update', ['only' => ['update']]);
    $this->middleware('permission:user_income.delete|user_expense.delete', ['only' => ['destroy']]);
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

    if ($canAccess) {
      $transfers = DB::table('transfers')
                      ->join('transfer_categories', function($join) {
                        $join->on('transfers.transfer_category_id', '=', 'transfer_categories.id');
                      })
                      ->join('transfer_types', function($join) {
                        $join->on('transfers.transfer_type_id', '=', 'transfer_types.id');
                      })
                      ->when($transferType != 'both', function($q) use($transferType) {
                        return $q->where('transfer_types.name', '=', $transferType);
                      })
                      ->get([
                        'transfers.name as name',
                        'transfers.created_at as created_at',
                        'transfers.amount as amount',
                        'transfer_categories.name as transfer_category_name',
                        'transfers.transfer_category_id as transfer_category_id',
                        'transfer_types.name as transfer_type_name',
                        'transfer_types.id as transfer_type_id',
                        'transfer_categories.color as transfer_category_color'
                      ]);

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
