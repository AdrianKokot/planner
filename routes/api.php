<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
  Route::apiResource('/event', 'API\EventController');
  Route::apiResource('/log', 'API\LogController');
  Route::apiResource('/user', 'API\UserController');
  Route::apiResource('/permission', 'API\PermissionController');
  Route::apiResource('/role', 'API\RoleController');
});
Route::apiResource('/transfer', 'API\TransferController');

