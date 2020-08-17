<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::get('/login', 'HomeController@index')->name('login');
Route::get('/{any}', 'HomeController@index');

Route::post('/login', 'API\AuthController@login');
Route::post('/register', 'API\AuthController@register');
