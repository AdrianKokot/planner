<?php

namespace App\Http\Controllers\API;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Log;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    // TODO add privileges check
    if (true) {
      $startTimestamp = $request->query('start');
      $endTimestamp = $request->query('end');
      $userId = $request->query('user_id') ?? Auth::user()->id;

      $events = [];

      if ($startTimestamp != null && $endTimestamp != null) {
        $events = DB::table('events')
          ->where('start', '>=', $startTimestamp)
          ->where('end', '<=', $endTimestamp)
          ->where('user_id', '=', $userId)
          ->get('*');
      } else {
        $events = DB::table('events')->get('*');
      }

      return response($events, 200);
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
    $user = Auth::user();
    if (true) {
      $validatedData = $request->validate([
        'start' => 'required|string',
        'end' => 'required|string',
        'title' => 'required|string|max:50',
        'color' => 'required|string|max:32',
        'description' => 'nullable|string|max:255'
      ]);

      $validatedData['user_id'] = $user->id;

      if (empty($validatedData['description']))
        $validatedData['description'] = '';

      $event = Event::create($validatedData);

      if ($event != null) {
        Log::log($user, $event, 'event', 'create', $validatedData);
        return response($event, 200);
      } else {
        return response('', 500);
      }
    }

    return response('Access deined.', 403);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Event  $event
   * @return \Illuminate\Http\Response
   */
  public function show(Event $event)
  {
    // TODO add privileges check
    if (true) {
      return response($event, 200);
    }
    return response('Access deined.', 403);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Event  $event
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Event $event)
  {
    // TODO add privileges check
    if (true) {
      $validatedData = $request->validate([
        'start' => 'nullable|string',
        'end' => 'nullable|string',
        'title' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:32',
        'description' => 'nullable|string|max:255'
      ]);

      if (empty($validatedData['description']))
        $validatedData['description'] = '';

      $oldEvent = clone $event;

      if ($event->update($validatedData)) {
        Log::log(Auth::user(), $oldEvent, 'event', 'update', $validatedData);

        return response($event, 200);
      } else {
        return response('', 500);
      }
    }

    return response('Access deined.', 403);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Event  $event
   * @return \Illuminate\Http\Response
   */
  public function destroy(Event $event)
  {
    // TODO add privileges check
    if (true) {
      if ($event->delete()) {
        Log::log(Auth::user(), $event, 'event', 'delete');
        return response(['id' => $event->id], 200);
      }
      return response('', 500);
    }
    return response('Access deined.', 403);
  }
}
