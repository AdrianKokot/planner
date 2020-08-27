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
  function __construct()
  {
    $this->middleware('permission:user_event.read', ['only' => ['index', 'show']]);
    $this->middleware('permission:user_event.create', ['only' => ['store']]);
    $this->middleware('permission:user_event.update', ['only' => ['update']]);
    $this->middleware('permission:user_event.delete', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $startTimestamp = $request->query('start');
    $endTimestamp = $request->query('end');
    $user = Auth::user();

    $events = [];

    if ($startTimestamp != null && $endTimestamp != null) {
      $events = Event::where('start', '>=', $startTimestamp)->where('end', '<=', $endTimestamp)->where('user_id', '=', $user->id)->with('expenses')->get();
    } else {
      $events = Event::with('expenses')->get();
    }

    return response($events);
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
      'start' => 'required|date',
      'end' => 'required|date',
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
      return response($event);
    } else {
      return response(['message' => 'Something went wrong.'], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Event  $event
   * @return \Illuminate\Http\Response
   */
  public function show(Event $event)
  {
    $user = Auth::user();

    if ($event->id == $user->id) {
      return response($event);
    }

    abort(403);
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
    $validatedData = $request->validate([
      'start' => 'nullable|string',
      'end' => 'nullable|string',
      'title' => 'nullable|string|max:50',
      'color' => 'nullable|string|max:32',
      'description' => 'nullable|string|max:255'
    ]);

    $user = Auth::user();

    if ($event->user_id == $user->id) {

      if (empty($validatedData['description']))
        $validatedData['description'] = '';

      $oldEvent = clone $event;

      if ($event->update($validatedData)) {
        Log::log($user, $oldEvent, 'event', 'update', $validatedData);

        return response($event);
      } else {
        return response(['message' => 'Something went wrong.'], 500);
      }
    }

    abort(403);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Event  $event
   * @return \Illuminate\Http\Response
   */
  public function destroy(Event $event)
  {
    $user = Auth::user();

    if ($event->user_id == $user->id) {

      if ($event->delete()) {
        Log::log(Auth::user(), $event, 'event', 'delete');
        return response(['id' => $event->id]);
      }

      return response(['message' => 'Something went wrong.'], 500);
    }

    abort(403);
  }
}
