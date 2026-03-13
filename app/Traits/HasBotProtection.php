<?php

namespace App\Traits;

use Illuminate\Support\Facades\RateLimiter;

/**
 * Provides honeypot and rate-limiting protection for public-facing Livewire forms.
 *
 * Usage:
 *   1. Add `use HasBotProtection;` to your Livewire component.
 *   2. Place `<x-honeypot />` inside the form in the corresponding Blade view.
 *      The component renders a hidden off-screen input bound to `$website` via
 *      `wire:model`. Humans never see or fill it; bots typically do.
 *   3. Call `$this->isHoneypotFilled()` and `$this->isRateLimited()` at the top
 *      of any form submission method before validation runs.
 *
 * @see resources/views/components/honeypot.blade.php
 */
trait HasBotProtection
{
    public string $website = ''; // honeypot — bound by <x-honeypot />

    protected function isHoneypotFilled(): bool
    {
        return $this->website !== '';
    }

    protected function rateLimitKey(): string
    {
        return __METHOD__.':'.request()->ip();
    }

    protected function isRateLimited(int $maxAttempts = 5, int $decaySeconds = 300): bool
    {
        $key = $this->rateLimitKey();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return true;
        }

        RateLimiter::hit($key, $decaySeconds);

        return false;
    }

    protected function rateLimitAvailableIn(): int
    {
        $key = $this->rateLimitKey();

        return RateLimiter::availableIn($key);
    }
}
