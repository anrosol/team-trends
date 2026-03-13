<?php

use Illuminate\Support\Facades\Route;

include 'web/auth.php';

include 'web/email-verification.php';

Route::livewire('/', 'pages::index')->name('pages.index');

Route::livewire('/passphrase/{token}', 'pages::teams.passphrase')->name('pages.teams.passphrase');
Route::livewire('/respondent', 'pages::teams.respondent')->name('pages.teams.respondent');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('pages.dashboard');

    Route::livewire('/users', 'pages::users.index')->name('pages.users.index');
    Route::livewire('/users/create', 'pages::users.create')->name('pages.users.create');
    Route::livewire('/users/{user}/edit', 'pages::users.edit')->name('pages.users.edit');

    Route::livewire('/teams', 'pages::teams.index')->name('pages.teams.index');
    Route::livewire('/teams/create', 'pages::teams.create')->name('pages.teams.create');
    Route::livewire('/teams/{team}/edit', 'pages::teams.edit')->name('pages.teams.edit');
});
