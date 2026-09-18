<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('auth-signin', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('auth-signup', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.attempt');
});

Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Public browsing — anyone can look for events, no login required.
Route::get('/', [PublicEventController::class, 'home'])->name('home');
Route::get('explorar', [PublicEventController::class, 'index'])->name('explore.index');
Route::get('eventos/{event:slug}', [PublicEventController::class, 'show'])->name('explore.show');

Route::middleware('auth')->group(function () {
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/saved', [EventController::class, 'saved'])->name('saved');
        Route::get('/{event}/dashboard', [EventController::class, 'dashboard'])->name('dashboard');
        Route::get('/{event}/attendees.csv', [EventController::class, 'attendees'])->name('attendees');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        Route::post('/{event}/save', [EventController::class, 'save'])->name('save');
        Route::delete('/{event}/save', [EventController::class, 'unsave'])->name('unsave');
    });

    Route::prefix('eventos/{event:slug}')->group(function () {
        Route::post('/reservar', [TicketController::class, 'reserve'])->name('tickets.reserve');
    });

    Route::prefix('mis-entradas')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
    });

    Route::prefix('entradas/{ticket}')->name('tickets.')->group(function () {
        Route::post('/cancelar', [TicketController::class, 'cancel'])->name('cancel');
        Route::get('/qr', [TicketController::class, 'qr'])->name('qr');
    });

    Route::prefix('organizaciones')->name('organizations.')->group(function () {
        Route::get('/', [OrganizationController::class, 'index'])->name('index');
        Route::get('/create', [OrganizationController::class, 'create'])->name('create');
        Route::post('/', [OrganizationController::class, 'store'])->name('store');
        Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])->name('edit');
        Route::put('/{organization}', [OrganizationController::class, 'update'])->name('update');
        Route::delete('/{organization}', [OrganizationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('perfil')->name('profile.')->group(function () {
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
            Route::post('/{user}/block', [\App\Http\Controllers\Admin\UserController::class, 'block'])->name('block');
            Route::post('/{user}/unblock', [\App\Http\Controllers\Admin\UserController::class, 'unblock'])->name('unblock');
            Route::post('/{user}/make-admin', [\App\Http\Controllers\Admin\UserController::class, 'makeAdmin'])->name('make-admin');
            Route::post('/{user}/revoke-admin', [\App\Http\Controllers\Admin\UserController::class, 'revokeAdmin'])->name('revoke-admin');
        });

        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\EventController::class, 'index'])->name('index');
            Route::post('/{event}/block', [\App\Http\Controllers\Admin\EventController::class, 'block'])->name('block');
            Route::post('/{event}/unblock', [\App\Http\Controllers\Admin\EventController::class, 'unblock'])->name('unblock');
            Route::delete('/{event}', [\App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('destroy');
        });
    });

    Route::get('{page}', [DashboardController::class, 'index'])->where('page', '[A-Za-z0-9\-]+');
});
