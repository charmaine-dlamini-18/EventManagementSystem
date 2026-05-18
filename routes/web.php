<?php

use App\Http\Controllers\DashboardControllerCMS;
use App\Http\Controllers\EventControllerCMS;
use App\Http\Controllers\RegistrationControllerCMS;
use App\Http\Controllers\UserControllerCMS;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'))->name('home');

Route::get('/events', [EventControllerCMS::class, 'index'])->name('events.index');
Route::get('/events/calendar', [EventControllerCMS::class, 'calendar'])->name('events.calendar');

Route::middleware(['auth', 'verified', 'log.requests'])->group(function () {
    Route::get('/dashboard', [DashboardControllerCMS::class, 'index'])->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\ProfileControllerCMS::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileControllerCMS::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileControllerCMS::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:organizer')->group(function () {
        Route::get('/events/create', [EventControllerCMS::class, 'create'])->name('events.create');
        Route::post('/events', [EventControllerCMS::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventControllerCMS::class, 'edit'])->name('events.edit');
        Route::patch('/events/{event}', [EventControllerCMS::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventControllerCMS::class, 'destroy'])->name('events.destroy');
    });

    Route::post('/events/{event}/register', [RegistrationControllerCMS::class, 'store'])->name('events.register');
    Route::delete('/events/{event}/register', [RegistrationControllerCMS::class, 'unregister'])->name('events.unregister');

    Route::get('/registrations', [RegistrationControllerCMS::class, 'index'])->name('registrations.index');

    Route::middleware('role:organizer')->group(function () {
        Route::post('/registrations/{registration}/approve', [RegistrationControllerCMS::class, 'approve'])->name('registrations.approve');
        Route::post('/registrations/{registration}/decline', [RegistrationControllerCMS::class, 'decline'])->name('registrations.decline');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserControllerCMS::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserControllerCMS::class, 'create'])->name('users.create');
        Route::post('/users', [UserControllerCMS::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserControllerCMS::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserControllerCMS::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserControllerCMS::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserControllerCMS::class, 'destroy'])->name('users.destroy');
    });
});

Route::get('/events/{event}', [EventControllerCMS::class, 'show'])->name('events.show');

require __DIR__.'/auth.php';