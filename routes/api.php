<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes

Route::post('/login', [AuthController::class, 'login']);
Route::post('/users/register', [UserController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth');

Route::middleware(['jwt.auth', 'throttle:api', \App\Http\Middleware\MonitorResponseTime::class])->group(function () {
    // User routes
    Route::get('/users', [UserController::class, 'getAllUsers']);

    // Transaction routes
    Route::prefix('transactions')->group(function () {
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::put('/{id}', [TransactionController::class, 'update']);
        Route::delete('/{id}', [TransactionController::class, 'destroy']);
    });

    // Report routes
    Route::post('/reports/monthly', [ReportController::class, 'monthlyReport']);

    // Route for managing budget
    Route::put('/budgets/{user_id}', [BudgetController::class, 'updateBudget']);
    Route::post('/budgets/{user_id}', [BudgetController::class, 'store']);

    Route::post('/reminders', [ReminderController::class, 'store']);
});
