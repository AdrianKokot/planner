<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
  Route::apiResource('/events', 'API\EventController');
});
