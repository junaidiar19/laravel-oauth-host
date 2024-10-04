<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', [UserController::class, 'getUser'])->middleware('scopes:view-user');
});
