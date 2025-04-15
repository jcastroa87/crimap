<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CrimeDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// Public API endpoint for crime data (requires valid API key)
Route::get('/crimes', [CrimeDataController::class, 'index'])->name('api.crimes');

// User authentication endpoint
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
