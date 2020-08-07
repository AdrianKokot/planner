<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index() {
      return response([
        ['id' => 1, 'title' => 'my event 1', 'start' => '2020-08-07', 'color' => 'var(--primary)', 'textColor' => 'white'],
        ['id' => 2, 'title' => 'my event 2', 'start' => '2020-08-06', 'color' => 'var(--purple)', 'textColor' => 'white'],
        ['id' => 3, 'title' => 'my event 3', 'start' => '2020-08-08', 'color' => 'var(--indigo)', 'textColor' => 'white'],
        ['id' => 4, 'title' => 'my event 4', 'start' => '2020-08-09', 'color' => 'var(--blue)', 'textColor' => 'white']
      ],200);
    }
}
