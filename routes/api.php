<?php

use App\Http\Controllers\v1\EventController;
use App\Http\Controllers\v1\ReportController;
use App\Http\Controllers\v1\TicketController;
use App\Http\Controllers\v1\TransactionController;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\UtilityController;
use App\Http\Middleware\VerifyUser;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->namespace('App\\Http\\Controllers\\v1')->group(function () {
    // Users
    Route::post('/users/login', [UserController::class, 'login']);

    Route::middleware([VerifyUser::class,])->group(function () {
        // Users
        Route::get('/users/profile', [UserController::class, 'profile']);

        // Events
        Route::post('/events/create', [EventController::class, 'create']);
        Route::get('/events/view/{id}', [EventController::class, 'view'])
            ->where('id', '[0-9]+');
        Route::post('/events/update/{id}', [EventController::class, 'update'])
            ->where('id', '[0-9]+');
        Route::delete('events/delete/{id}', [EventController::class, 'delete'])
            ->where('id', '[0-9]+');
        Route::get('/events', [EventController::class, 'index']);
        Route::get('/events/list', [EventController::class, 'getEventList']);

        // Tickets
        Route::post('/tickets/create', [TicketController::class, 'create']);
        Route::get('/tickets/view/{id}', [TicketController::class, 'view'])
            ->where('id', '[0-9]+');
        Route::put('tickets/update/{id}', [TicketController::class, 'update'])
            ->where('id', '[0-9]+');
        Route::delete('tickets/delete/{id}', [TicketController::class, 'delete'])
            ->where('id', '[0-9]+');
        Route::get('/tickets', [TicketController::class, 'index']);
        Route::get('/tickets/{id}', [TicketController::class, 'getTicketByEvent'])
            ->where('id', '[0-9]+');

        // Transactions
        Route::post('/transactions/create', [TransactionController::class, 'create']);
        Route::get('/transactions/view/{id}', [TransactionController::class, 'view'])
            ->where('id', '[0-9]+');
        Route::put('transactions/update/{id}', [TransactionController::class, 'update'])
            ->where('id', '[0-9]+');
        Route::delete('transactions/delete/{id}', [TransactionController::class, 'delete'])
            ->where('id', '[0-9]+');
        Route::get('/transactions', [TransactionController::class, 'index']);

        // Utilities
        Route::get('/utilities/categories', [UtilityController::class, 'getListCategory']);
        Route::get('/utilities/events', [UtilityController::class, 'getListMasterEvent']);
        Route::get('/utilities/provinces', [UtilityController::class, 'getListProvince']);
        Route::get('/utilities/roles', [UtilityController::class, 'getListUserRole']);

        // Reports
        Route::post('/reports/export', [ReportController::class, 'export']);
    });
});
