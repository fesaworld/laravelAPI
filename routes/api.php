<?php

require_once('includes/auth.php');

use Illuminate\Support\Facades\Route;

Route::group(
    ['middleware' => 'auth:api'],
    function() {
        require_once('includes/user.php');
        require_once('includes/profile.php');
    }
);
