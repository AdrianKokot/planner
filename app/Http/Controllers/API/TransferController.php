<?php

namespace App\Http\Controllers\API;

use App\Log;
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
        ->join('transfer_categories', function ($join) {
          $join->on('transfers.transfer_category_id', '=', 'transfer_categories.id');
        })
        ->join('transfer_types', function ($join) {
          $join->on('transfers.transfer_type_id', '=', 'transfer_types.id');
        })
        ->when($transferType != 'both', function ($q) use ($transferType) {
          return $q->where('transfer_types.name', '=', $transferType);
        })
        ->where('transfers.user_id', '=', $user->id)
        ->orderBy('created_at', 'DESC')
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
    $user = Auth::user();

    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'created_at' => 'required|date',
      'amount' => 'required|numeric',
      'transfer_type_id' => 'required|numeric|exists:transfer_types,id',
      'transfer_category_id' => 'nullable|numeric|exists:transfer_categories,id',
      'event_id' => 'nullable|numeric|exists:events,id'
    ]);

    $transferType = DB::table('transfer_types')->where('id', '=', $validatedData['transfer_type_id']);

    if (($transferType == 'income' && $user->can('user_income.create')) || ($transferType == 'expense' && $user->can('user_expense.create'))) {

      $validatedData['user_id'] = $user->id;

      $transfer = Transfer::create($validatedData);

      if ($transfer != null) {
        Log::log($user, $transfer, 'transaction', 'create', $validatedData);
        return response($transfer);
      }

      return response(['message' => 'Something went wrong.', 500]);
    }

    abort(403);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Transfer  $transfer
   * @return \Illuminate\Http\Response
   */
  public function show(Transfer $transfer)
  {
    $user = Auth::user();

    if ($transfer->user_id == $user->id) {
      return response($transfer);
    }

    abort(403);
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
    $user = Auth::user();

    $validatedData = $request->validate([
      'name' => 'nullable|string|max:255',
      'created_at' => 'nullable|date',
      'amount' => 'nullable|numeric',
      'transfer_type_id' => 'nullable|numeric|exists:transfer_types,id',
      'transfer_category_id' => 'nullable|numeric|exists:transfer_categories,id',
      'event_id' => 'nullable|numeric|exists:events,id'
    ]);

    if ($transfer->user_id == $user->id) {

      $transferType = DB::table('transfer_types')->where('id', '=', $validatedData['transfer_type_id']);

      if (($transferType == 'income' && $user->can('user_income.update')) || ($transferType == 'expense' && $user->can('user_expense.update'))) {

        $oldTransfer = clone $transfer;

        if ($transfer->update($validatedData)) {
          Log::log($user, $oldTransfer, 'transaction', 'update', $validatedData);
          return response($transfer);
        }

        return response(['message' => 'Something went wrong.', 500]);
      }
    }

    abort(403);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Transfer  $transfer
   * @return \Illuminate\Http\Response
   */
  public function destroy(Transfer $transfer)
  {
    $user = Auth::user();

    if ($transfer->user_id == $user->id) {

      if ($transfer->delete()) {
        Log::log($user, $transfer, 'transaction', 'delete');
        return response($transfer);
      }

      return response(['message' => 'Someting went wrong.'], 500);
    }

    abort(403);
  }
}
