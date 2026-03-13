<?php

use App\Models\Respondent;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('appex.passphrase.pepper', 'test-pepper');
});

it('hashes a passphrase using sha256 and the pepper', function () {
    $hash = Respondent::hashPassphrase('correct-horse-battery-staple');

    expect($hash)->toBe(hash('sha256', 'test-pepper'.'correct-horse-battery-staple'));
});

it('produces a 64-character lowercase hex hash', function () {
    $hash = Respondent::hashPassphrase('some-passphrase');

    expect($hash)
        ->toHaveLength(64)
        ->toMatch('/^[a-f0-9]+$/');
});

it('produces different hashes for different passphrases', function () {
    $hash1 = Respondent::hashPassphrase('passphrase-one');
    $hash2 = Respondent::hashPassphrase('passphrase-two');

    expect($hash1)->not->toBe($hash2);
});

it('produces different hashes for the same passphrase with a different pepper', function () {
    Config::set('appex.passphrase.pepper', 'pepper-a');
    $hash1 = Respondent::hashPassphrase('same-passphrase');

    Config::set('appex.passphrase.pepper', 'pepper-b');
    $hash2 = Respondent::hashPassphrase('same-passphrase');

    expect($hash1)->not->toBe($hash2);
});

it('throws when the pepper is not configured', function () {
    Config::set('appex.passphrase.pepper', null);

    Respondent::hashPassphrase('any-passphrase');
})->throws(Exception::class, 'Missing PASSPHRASE_PEPPER value.');
