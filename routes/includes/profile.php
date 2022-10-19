<?php

use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix'   => 'profile'], function () {
        Route::get('/', 'ProfileController@index');
        Route::post('/', 'ProfileController@update');
});
