<?php

use App\Traits\HasBotProtection;
use Illuminate\Support\Facades\RateLimiter;

// Anonymous class that uses the trait, exposing protected methods for testing.
function makeBotProtectionComponent(string $website = ''): object
{
    return new class($website)
    {
        use HasBotProtection;

        public function __construct(string $website)
        {
            $this->website = $website;
        }

        public function checkHoneypot(): bool
        {
            return $this->isHoneypotFilled();
        }

        public function checkRateLimited(int $max = 5, int $decay = 300): bool
        {
            return $this->isRateLimited($max, $decay);
        }

        public function checkAvailableIn(): int
        {
            return $this->rateLimitAvailableIn();
        }

        public function getRateLimitKey(): string
        {
            return $this->rateLimitKey();
        }
    };
}

it('returns false when honeypot field is empty', function () {
    expect(makeBotProtectionComponent('')->checkHoneypot())->toBeFalse();
});

it('returns true when honeypot field is filled', function () {
    expect(makeBotProtectionComponent('bot was here')->checkHoneypot())->toBeTrue();
});

it('returns true when honeypot field contains only spaces', function () {
    expect(makeBotProtectionComponent(' ')->checkHoneypot())->toBeTrue();
});

it('is not rate limited on first attempt', function () {
    RateLimiter::clear(makeBotProtectionComponent()->getRateLimitKey());

    expect(makeBotProtectionComponent()->checkRateLimited(5, 300))->toBeFalse();
});

it('increments the rate limiter on each call', function () {
    $component = makeBotProtectionComponent();
    $key = $component->getRateLimitKey();
    RateLimiter::clear($key);

    $component->checkRateLimited(5, 300);
    $component->checkRateLimited(5, 300);

    expect(RateLimiter::attempts($key))->toBe(2);
});

it('is rate limited after exceeding max attempts', function () {
    $component = makeBotProtectionComponent();
    $key = $component->getRateLimitKey();
    RateLimiter::clear($key);

    // Exhaust the limit (max = 2).
    $component->checkRateLimited(2, 300);
    $component->checkRateLimited(2, 300);

    expect($component->checkRateLimited(2, 300))->toBeTrue();
});

it('rateLimitAvailableIn returns an integer', function () {
    $component = makeBotProtectionComponent();
    RateLimiter::clear($component->getRateLimitKey());

    expect($component->checkAvailableIn())->toBeInt();
});

it('rateLimitKey includes the request IP', function () {
    $key = makeBotProtectionComponent()->getRateLimitKey();

    expect($key)->toContain(request()->ip());
});
