<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventControllerCMS;
use App\Http\Controllers\RegistrationControllerCMS;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'))->name('home');

Route::get('/events', [EventControllerCMS::class, 'index'])->name('events.index');
Route::get('/events/calendar', [EventControllerCMS::class, 'calendar'])->name('events.calendar');
Route::get('/events/{event}', [EventControllerCMS::class, 'show'])->name('events.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin|organizer')->group(function () {
        Route::get('/events/create', [EventControllerCMS::class, 'create'])->name('events.create');
        Route::post('/events', [EventControllerCMS::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventControllerCMS::class, 'edit'])->name('events.edit');
        Route::patch('/events/{event}', [EventControllerCMS::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventControllerCMS::class, 'destroy'])->name('events.destroy');
    });

    Route::post('/events/{event}/register', [RegistrationControllerCMS::class, 'store'])->name('events.register');
    Route::delete('/events/{event}/register', [RegistrationControllerCMS::class, 'unregister'])->name('events.unregister');

    Route::get('/registrations', [RegistrationControllerCMS::class, 'index'])->name('registrations.index');

    Route::middleware('role:admin|organizer')->group(function () {
        Route::post('/registrations/{registration}/approve', [RegistrationControllerCMS::class, 'approve'])->name('registrations.approve');
        Route::post('/registrations/{registration}/decline', [RegistrationControllerCMS::class, 'decline'])->name('registrations.decline');
    });
});

require __DIR__.'/auth.php';