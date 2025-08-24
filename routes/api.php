<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AIAdvisorController;
use App\Http\Controllers\API\V1\FileUploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// File Upload Routes
Route::middleware(['auth:sanctum', 'throttle:6,1'])->group(function () {
    Route::post('/files/upload', [FileUploadController::class, 'upload']);
    Route::delete('/files/{fileId}', [FileUploadController::class, 'destroy']);
    Route::get('/files', [FileUploadController::class, 'index']);
    Route::get('/files/{fileId}', [FileUploadController::class, 'show']);
});

// AI Advisor API endpoint
Route::post('/advisor', [AIAdvisorController::class, 'analyze']);
