<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/login', 'pages::auth.login')->name('login');

Route::get('/auth/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('pages.index');
})->name('pages.auth.logout');

Route::livewire('/auth/register', 'pages::auth.register')->name('pages.auth.register');

Route::livewire('/auth/forgot-password', 'pages::auth.forgot-password')->name('pages.auth.forgot-password');

Route::livewire('/auth/reset-password/{token}', 'pages::auth.reset-password')->name('pages.auth.reset-password');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/profile', 'pages::auth.profile')->name('pages.auth.profile');
});
