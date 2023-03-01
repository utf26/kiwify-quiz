<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;


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

require __DIR__.'/auth.php';

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show']);
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update']);
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy']);

    Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store']);
    Route::put('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update']);
    Route::delete('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy']);
});

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'errors'  => ['message' => 'Route Not Found.'],
        'data'    => [],
    ], 204);
});
