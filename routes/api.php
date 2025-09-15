<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\KeywordController;

// Rate Limit to prevent users from sending more than 120 requests per minute and thus avoid DDOS attacks
Route::middleware('throttle:120,1')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{id}/toggle', [TaskController::class, 'toggle']);

    // My interpretation of the problem
    Route::patch('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

    // Opcional
    Route::get('/keywords', [KeywordController::class, 'index']);
    Route::post('/keywords', [KeywordController::class, 'store']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
