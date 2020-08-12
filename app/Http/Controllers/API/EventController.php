<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index() {
      return response([
        ['id' => 1, 'title' => 'my event 1', 'start' => '2020-08-07T09:30:00', 'end'=>'2020-08-07T10:30:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        ['id' => 5, 'title' => 'my event 5', 'start' => '2020-08-07T11:30:00', 'end'=>'2020-08-07T12:00:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        ['id' => 6, 'title' => 'my event 6', 'start' => '2020-08-07T14:30:00', 'end'=>'2020-08-07T14:50:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        ['id' => 7, 'title' => 'my event 7', 'start' => '2020-08-07T15:00:00', 'end'=>'2020-08-07T15:30:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        ['id' => 8, 'title' => 'my event 8', 'start' => '2020-08-07T15:30:00', 'end'=>'2020-08-07T16:10:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        // ['id' => 9, 'title' => 'my event 9', 'start' => '2020-08-07T14:30:00', 'end'=>'2020-08-07T14:30:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        // ['id' => 10, 'title' => 'my event 10', 'start' => '2020-08-07T14:30:00', 'end'=>'2020-08-07T14:30:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        // ['id' => 11, 'title' => 'my event 11', 'start' => '2020-08-07T14:30:00', 'end'=>'2020-08-07T14:30:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        // ['id' => 12, 'title' => 'my event 12', 'start' => '2020-08-07T14:30:00', 'end'=>'2020-08-07T14:30:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        // ['id' => 13, 'title' => 'my event 13', 'start' => '2020-08-07T14:30:00', 'end'=>'2020-08-07T14:30:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        // ['id' => 14, 'title' => 'my event 14', 'start' => '2020-08-07T14:30:00', 'end'=>'2020-08-07T14:30:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        // ['id' => 15, 'title' => 'my event 15', 'start' => '2020-08-07T14:30:00', 'end'=>'2020-08-07T14:30:00', 'color' => 'var(--primary)', 'textColor' => 'white'],
        ['id' => 2, 'title' => 'my event 2', 'start' => '2020-08-06T08:30:00', 'end'=>'2020-08-06T12:30:00', 'color' => 'var(--purple)', 'textColor' => 'white'],
        ['id' => 3, 'title' => 'my event 3', 'start' => '2020-08-08T10:10:00', 'end'=>'2020-08-08T12:00:00', 'color' => 'var(--indigo)', 'textColor' => 'white'],
        ['id' => 4, 'title' => 'my event 4', 'start' => '2020-08-09T16:00:00', 'end'=>'2020-08-09T16:45:00', 'color' => 'var(--blue)', 'textColor' => 'white']
      ],200);
    }
}
