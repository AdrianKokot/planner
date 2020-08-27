<?php

namespace App\Http\Controllers\API;

use App\Log;
use App\TransferCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransferCategoryController extends Controller
{
  function __construct()
  {
    $this->middleware('permission:user_income.create|user_expense.create|transfer_category.read', ['only' => ['index', 'show']]);
    $this->middleware('permission:transfer_category.create', ['only' => ['store']]);
    $this->middleware('permission:transfer_category.update', ['only' => ['update']]);
    $this->middleware('permission:transfer_category.delete', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return response(DB::table('transfer_categories')->where('name', '<>', 'income')->get());
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
      'color' => 'required|string|max:32'
    ]);

    $transferCategory = TransferCategory::create($validatedData);

    if ($transferCategory != null) {
      Log::log(Auth::user(), $transferCategory, 'transfer category', 'create', $validatedData);
      return response($transferCategory);
    }

    return response(['message' => 'Something went wrong.'], 500);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\TransferCategory  $transferCategory
   * @return \Illuminate\Http\Response
   */
  public function show(TransferCategory $transferCategory)
  {
    return response($transferCategory);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\TransferCategory  $transferCategory
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, TransferCategory $transferCategory)
  {
    $validatedData = $request->validate([
      'name' => 'nullable|string|max:255',
      'color' => 'nullable|string|max:32'
    ]);

    $oldTransferCategory = clone $transferCategory;

    if ($transferCategory->update($validatedData)) {
      Log::log(Auth::user(), $oldTransferCategory, 'transfer category', 'update', $validatedData);
      return response($transferCategory);
    }

    return response(['message' => 'Something went wrong.'], 500);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\TransferCategory  $transferCategory
   * @return \Illuminate\Http\Response
   */
  public function destroy(TransferCategory $transferCategory)
  {
    if ($transferCategory->delete()) {
      Log::log(Auth::user(), $transferCategory, 'transfer category', 'delete');
      return response(['id' => $transferCategory->id]);
    }
    return response(['message' => 'Something went wrong.'], 500);
  }
}
