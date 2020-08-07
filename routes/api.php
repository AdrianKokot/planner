<?php

use Illuminate\Support\Facades\Route;

Route::get('/events', 'API\EventController@index');
