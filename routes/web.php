<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'HomeController@index')->name('login');

Route::post('/login', 'API\AuthController@login');

Route::get('{any}', 'HomeController@index')->where('any', '.*');
