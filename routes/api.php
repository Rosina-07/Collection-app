<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(\App\Http\Controllers\CarController::class)->group(function () {
    Route::get('/cars', 'all');
    Route::get('/cars/{id}', 'find');
    Route::post('/cars', 'create');
    Route::put('/cars/{id}', 'update');
    Route::delete('/cars/{id}', 'delete');
});
