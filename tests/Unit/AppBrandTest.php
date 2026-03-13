<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    $this->html = Blade::render('<x-app-brand />');
});

it('renders without error', function () {
    expect($this->html)->toBeString()->not->toBeEmpty();
});

it('renders a link to the homepage', function () {
    expect($this->html)->toContain('href="/"');
});

it('renders the logo image', function () {
    expect($this->html)->toContain('src="/logo.svg"');
});

it('renders the app name from config', function () {
    expect($this->html)->toContain(config('app.name'));
});

it('reflects a changed app name', function () {
    Config::set('app.name', 'Custom Brand Name');

    $html = Blade::render('<x-app-brand />');

    expect($html)->toContain('Custom Brand Name');
});

it('includes the hidden-when-collapsed wrapper for the expanded state', function () {
    expect($this->html)->toContain('hidden-when-collapsed');
});

it('includes the display-when-collapsed wrapper for the collapsed state', function () {
    expect($this->html)->toContain('display-when-collapsed');
});
