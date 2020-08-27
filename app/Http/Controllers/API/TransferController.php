<?php

namespace App\Http\Controllers\API;

use App\Log;
use App\Transfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\TransferType;
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
    $start = $request->query('start') ?? null;

    $user = Auth::user();

    $transferType = $user->can('user_income.read')
                    ? ($user->can('user_expense.read') ? 'both' : 'income')
                    : ($user->can('user_expense.read') ? 'expense' : 'none');

    $transfers = DB::table('transfers')
                    ->leftJoin('events', function($join) {
                      $join->on('events.id', '=', 'transfers.event_id');
                    })
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
                    ->when($start != null, function ($q) use ($start) {
                      return $q->where('created_at', '>=', Carbon::parse($start));
                    })
                    ->orderBy('created_at', 'DESC')
                    ->get([
                      'transfers.id as id',
                      'transfers.name as name',
                      'transfers.created_at as created_at',
                      'transfers.amount as amount',
                      'transfer_categories.name as transfer_category_name',
                      'transfers.transfer_category_id as transfer_category_id',
                      'transfer_types.name as transfer_type_name',
                      'transfer_types.id as transfer_type_id',
                      'transfer_categories.color as transfer_category_color',
                      'events.id as event_id',
                      'events.title as event_title'
                    ]);

    return response($transfers);
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

    $transferType = DB::table('transfer_types')->where('id', '=', $validatedData['transfer_type_id'])->first()->name;

    if (($transferType == 'income' && $user->can('user_income.create')) || ($transferType == 'expense' && $user->can('user_expense.create'))) {

      $validatedData['user_id'] = $user->id;

      if ($transferType == 'income') {
        $validatedData['transfer_category_id'] = DB::table('transfer_categories')->where('name', '=', 'salary')->first()->id;
      }

      $transfer = Transfer::create($validatedData);

      if ($transfer != null) {
        Log::log($user, $transfer, 'transaction', 'create', $validatedData);

        $transferData = DB::table('transfers')
                            ->leftJoin('events', function($join) {
                              $join->on('events.id', '=', 'transfers.event_id');
                            })
                            ->join('transfer_categories', function ($join) {
                              $join->on('transfers.transfer_category_id', '=', 'transfer_categories.id');
                            })
                            ->join('transfer_types', function ($join) {
                              $join->on('transfers.transfer_type_id', '=', 'transfer_types.id');
                            })
                            ->where('transfers.id', '=', $transfer->id)
                            ->first([
                              'transfers.id as id',
                              'transfers.name as name',
                              'transfers.created_at as created_at',
                              'transfers.amount as amount',
                              'transfer_categories.name as transfer_category_name',
                              'transfers.transfer_category_id as transfer_category_id',
                              'transfer_types.name as transfer_type_name',
                              'transfer_types.id as transfer_type_id',
                              'transfer_categories.color as transfer_category_color',
                              'events.id as event_id',
                              'events.title as event_title'
                            ]);

        return response()->json($transferData);
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

      $transferType = DB::table('transfer_types')->where('id', '=', $validatedData['transfer_type_id'])->first()->name;

      if (($transferType == 'income' && $user->can('user_income.update')) || ($transferType == 'expense' && $user->can('user_expense.update'))) {

        if ($transferType == 'income') {
          $validatedData['transfer_category_id'] = DB::table('transfer_categories')->where('name', '=', 'salary')->first()->id;
        }

        $oldTransfer = clone $transfer;

        if ($transfer->update($validatedData)) {
          Log::log($user, $oldTransfer, 'transaction', 'update', $validatedData);
          $transferData = DB::table('transfers')
                            ->leftJoin('events', function($join) {
                              $join->on('events.id', '=', 'transfers.event_id');
                            })
                            ->join('transfer_categories', function ($join) {
                              $join->on('transfers.transfer_category_id', '=', 'transfer_categories.id');
                            })
                            ->join('transfer_types', function ($join) {
                              $join->on('transfers.transfer_type_id', '=', 'transfer_types.id');
                            })
                            ->where('transfers.id', '=', $transfer->id)
                            ->first([
                              'transfers.id as id',
                              'transfers.name as name',
                              'transfers.created_at as created_at',
                              'transfers.amount as amount',
                              'transfer_categories.name as transfer_category_name',
                              'transfers.transfer_category_id as transfer_category_id',
                              'transfer_types.name as transfer_type_name',
                              'transfer_types.id as transfer_type_id',
                              'transfer_categories.color as transfer_category_color',
                              'events.id as event_id',
                              'events.title as event_title'
                            ]);

          return response()->json($transferData);
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
