<?php

namespace App\Http\Controllers\API;

use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
  function __construct()
  {
    $this->middleware('permission:log.read', ['only' => ['index', 'show']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $logs = DB::table('logs')
      ->join('users', function ($join) {
        $join->on('logs.user_id', '=', 'users.id');
      })
      ->join('log_types', function ($join) {
        $join->on('log_types.id', '=', 'logs.log_type_id');
      })
      ->orderBy('logs.log_at', 'desc')
      ->get([
        'logs.log_at AS log_at',
        'logs.title AS log_title',
        'logs.description AS log_description',
        'log_types.name AS log_type_name',
        DB::raw("CONCAT(users.name, ' (<strong>', users.email, '</strong>)') AS user_name")
      ]);

    return response($logs, 200);
  }
}
