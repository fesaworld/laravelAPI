<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'    => 'auth',
    ], function () {
        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@login')->name('login');
    });

Route::group(
    [
        'middleware'    => 'auth:api',
        'prefix'        => 'auth',
    ],
    function() {
        Route::post('/logout', 'AuthController@logout');
    }
);
