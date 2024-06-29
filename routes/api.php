<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/prediction-flood', [ApiController::class, 'predictionFlood']);
Route::get('/today-flood', [ApiController::class, 'todayFlood']);
Route::get('/forecast-flood', [ApiController::class, 'forecastFlood']);
Route::get('/evacuation-point', [ApiController::class, 'getEvacuation']);