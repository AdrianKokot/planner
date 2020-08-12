<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index() {
      return response([
        ['id' => 1, 'title' => 'my event 1', 'start' => '2020-08-07T09:30:00', 'end'=>'2020-08-07T10:30:00', 'color' => 'var(--primary)', 'textColor' => 'white', 'description'=>'my event desription', 'expenses' => [
          ['name' => 'Snacks for guests', 'category' => 'Food', 'amount' => 249.99, 'id' => 1, 'currency' => 'PLN'],
        ]],
        ['id' => 5, 'title' => 'my event 5', 'start' => '2020-08-07T11:30:00', 'end'=>'2020-08-07T12:00:00', 'color' => 'var(--primary)', 'textColor' => 'white', 'expenses' => [
          ['name' => 'Snacks for guests', 'category' => 'Food', 'amount' => 249.99, 'id' => 1, 'currency' => 'PLN'],
        ]],
        ['id' => 6, 'title' => 'my event 6', 'start' => '2020-08-07T14:30:00', 'end'=>'2020-08-07T14:50:00', 'color' => 'var(--primary)', 'textColor' => 'white', 'description'=>'my event desription'],
        ['id' => 7, 'title' => 'my event 7', 'start' => '2020-08-07T15:00:00', 'end'=>'2020-08-07T15:30:00', 'color' => 'var(--primary)', 'textColor' => 'white', 'expenses' => [
          ['name' => 'Snacks for guests', 'category' => 'Food', 'amount' => 249.99, 'id' => 1, 'currency' => 'PLN'],
        ]],
        ['id' => 8, 'title' => 'my event 8', 'start' => '2020-08-07T15:30:00', 'end'=>'2020-08-07T16:10:00', 'color' => 'var(--primary)', 'textColor' => 'white', 'description'=>'my event desription'],
        ['id' => 2, 'title' => 'my event 2', 'start' => '2020-08-06T08:30:00', 'end'=>'2020-08-06T12:30:00', 'color' => 'var(--purple)', 'textColor' => 'white'],
        ['id' => 3, 'title' => 'my event 3', 'start' => '2020-08-08T10:10:00', 'end'=>'2020-08-08T12:00:00', 'color' => 'var(--indigo)', 'textColor' => 'white', 'description'=>'my event desription', 'expenses' => [
          ['name' => 'Snacks for guests', 'category' => 'Food', 'amount' => 249.99, 'id' => 1, 'currency' => 'PLN'],
        ]],
        ['id' => 4, 'title' => 'my event 4', 'start' => '2020-08-09T16:00:00', 'end'=>'2020-08-09T16:45:00', 'color' => 'var(--blue)', 'textColor' => 'white']
      ],200);
    }
}
