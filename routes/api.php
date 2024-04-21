<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(
    [
        'middleware' => 'auth:sanctum'
    ],
    function () {
        Route::get('/getdata', [TaskController::class, 'getData']);
        Route::post('/task/search', [TaskController::class, 'search']);
        Route::post('/task/update/{id}', [TaskController::class, 'taskUpdate']);
        Route::apiResource('/task', TaskController::class);
    }
);