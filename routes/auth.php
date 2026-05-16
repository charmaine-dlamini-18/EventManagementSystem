<?php

use App\Http\Controllers\Auth\AuthenticatedSessionControllerCMS;
use App\Http\Controllers\Auth\ConfirmablePasswordControllerCMS;
use App\Http\Controllers\Auth\EmailVerificationNotificationControllerCMS;
use App\Http\Controllers\Auth\EmailVerificationPromptControllerCMS;
use App\Http\Controllers\Auth\NewPasswordControllerCMS;
use App\Http\Controllers\Auth\PasswordControllerCMS;
use App\Http\Controllers\Auth\PasswordResetLinkControllerCMS;
use App\Http\Controllers\Auth\RegisteredUserControllerCMS;
use App\Http\Controllers\Auth\VerifyEmailControllerCMS;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserControllerCMS::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserControllerCMS::class, 'store']);

    Route::get('login', [AuthenticatedSessionControllerCMS::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionControllerCMS::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkControllerCMS::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkControllerCMS::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordControllerCMS::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordControllerCMS::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptControllerCMS::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailControllerCMS::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationControllerCMS::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordControllerCMS::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordControllerCMS::class, 'store']);

    Route::put('password', [PasswordControllerCMS::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionControllerCMS::class, 'destroy'])
        ->name('logout');
});