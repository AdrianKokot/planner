<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
    return response(DB::table('transfer_types')->all());
  }
}
