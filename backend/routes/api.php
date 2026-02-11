<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Odontology Module Routes
|--------------------------------------------------------------------------
*/
require app_path('Modules/Odontology/routes.php');

