<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'HomeController@index')->name('login');

Route::post('/login', 'API\AuthController@login');

Route::middleware('auth:sanctum')->group(function() {
  Route::post('/logout', 'API\AuthController@logout');
});

Route::get('{any}', 'HomeController@index')->where('any', '.*');
