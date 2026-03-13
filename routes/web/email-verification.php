<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::livewire('/email/verify', 'pages::email-verification.notice')
        ->name('verification.notice');

    Route::livewire('/email/verify/{id}/{hash}', 'pages::email-verification.verify')
        ->middleware('signed')
        ->name('verification.verify');

    // Route::livewire('/email/verification-notification', 'pages::email-verification.send')
    //     ->middleware('throttle:6,1')
    //     ->name('verification.send');

    // Route::livewire('/auth/verify-email', 'pages::auth.verify-email')->name('pages.auth.verify-email');
});
