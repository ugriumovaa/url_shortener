<?php

use App\Http\Controllers\LinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/links', [LinkController::class, 'store']);
Route::get('/links/{code}/stats', [LinkController::class, 'stats']);
