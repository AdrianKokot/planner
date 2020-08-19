<?php

namespace App\Http\Controllers\API;

use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LogController extends Controller
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
      $logs = DB::table('logs')
        ->join('users', function ($join) {
          $join->on('logs.user_id', '=', 'users.id');
        })
        ->join('log_types', function ($join) {
          $join->on('log_types.id', '=', 'logs.log_type_id');
        })
        ->leftJoin('events', function ($join) {
          $join->on('events.id', '=', 'logs.event_id');
        })
        ->orderBy('logs.log_at', 'desc')
        ->get([
          'events.start AS event_start',
          'events.end AS event_end',
          'events.description AS event_description',
          'events.title AS event_title',
          'logs.log_at AS log_at',
          'logs.title AS log_title',
          'logs.description AS log_description',
          'log_types.name AS log_type_name',
          DB::raw("CONCAT(users.name, ' (<strong>', users.email, '</strong>)') AS user_name")
        ]);

      return response($logs, 200);
    }

    return response('Access deined.', 403);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
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
   * @param  \App\Log  $log
   * @return \Illuminate\Http\Response
   */
  public function show(Log $log)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Log  $log
   * @return \Illuminate\Http\Response
   */
  public function edit(Log $log)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Log  $log
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Log $log)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Log  $log
   * @return \Illuminate\Http\Response
   */
  public function destroy(Log $log)
  {
    //
  }
}
