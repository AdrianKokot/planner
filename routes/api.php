<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
  Route::get('/events', 'API\EventController@index');
});
