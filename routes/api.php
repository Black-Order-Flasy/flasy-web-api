<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-prediction', [ApiController::class, 'getPrediction']);
Route::get('/get-all-prediction', [ApiController::class, 'getAllPrediction']);