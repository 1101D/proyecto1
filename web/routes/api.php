<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrganizerController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Eventos públicos (no requieren sesión)
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/upcoming', [EventController::class, 'upcoming']);
Route::get('/events/categories', [EventController::class, 'categories']);
Route::get('/events/{event}', [EventController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [ProfileController::class, 'show']);
    Route::patch('/me', [ProfileController::class, 'update']);

    // App de usuarios/asistentes
    Route::post('/events/{event}/like', [EventController::class, 'like']);
    Route::delete('/events/{event}/like', [EventController::class, 'unlike']);
    Route::get('/me/saved-events', [EventController::class, 'saved']);

    Route::post('/events/{event}/orders', [OrderController::class, 'store']);
    Route::get('/me/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    Route::get('/me/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::get('/tickets/{ticket}/qr', [TicketController::class, 'qr']);
    Route::post('/tickets/{ticket}/cancel', [TicketController::class, 'cancel']);

    // App de organizadores
    Route::prefix('organizer')->group(function () {
        Route::get('/dashboard', [OrganizerController::class, 'dashboard']);
        Route::get('/events/{event}/dashboard', [OrganizerController::class, 'eventDashboard']);
        Route::get('/events/{event}/attendees', [OrganizerController::class, 'attendees']);
        Route::get('/events/{event}/orders', [OrganizerController::class, 'orders']);
        Route::get('/events/{event}/sales', [OrganizerController::class, 'sales']);
        Route::post('/tickets/scan', [OrganizerController::class, 'scan']);
    });
});

Route::get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
