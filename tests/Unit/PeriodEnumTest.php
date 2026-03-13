<?php

use App\Enums\PeriodEnum;
use Carbon\Carbon;

// for()

it('returns the start of the week for WEEK', function () {
    $date = Carbon::parse('2026-03-04'); // Wednesday

    expect(PeriodEnum::WEEK->for($date))->toBe('2026-03-02'); // Monday
});

it('returns the same day for WEEK when the date is already a Monday', function () {
    $date = Carbon::parse('2026-03-02'); // Monday

    expect(PeriodEnum::WEEK->for($date))->toBe('2026-03-02');
});

it('returns the start of the current week for WEEK when the date is a Sunday', function () {
    $date = Carbon::parse('2026-03-08'); // Sunday

    expect(PeriodEnum::WEEK->for($date))->toBe('2026-03-02');
});

it('returns the first of the month for MONTH', function () {
    $date = Carbon::parse('2026-03-15');

    expect(PeriodEnum::MONTH->for($date))->toBe('2026-03-01');
});

it('returns the correct quarter start for each quarter', function () {
    expect(PeriodEnum::QUARTER->for(Carbon::parse('2026-01-15')))->toBe('2026-01-01'); // Q1
    expect(PeriodEnum::QUARTER->for(Carbon::parse('2026-04-15')))->toBe('2026-04-01'); // Q2
    expect(PeriodEnum::QUARTER->for(Carbon::parse('2026-07-15')))->toBe('2026-07-01'); // Q3
    expect(PeriodEnum::QUARTER->for(Carbon::parse('2026-10-15')))->toBe('2026-10-01'); // Q4
});

it('returns the correct quarter start on quarter boundary dates', function () {
    expect(PeriodEnum::QUARTER->for(Carbon::parse('2026-03-31')))->toBe('2026-01-01'); // last day of Q1
    expect(PeriodEnum::QUARTER->for(Carbon::parse('2026-04-01')))->toBe('2026-04-01'); // first day of Q2
});

it('returns the first of the year for YEAR', function () {
    $date = Carbon::parse('2026-08-20');

    expect(PeriodEnum::YEAR->for($date))->toBe('2026-01-01');
});

it('returns the first of the year for YEAR on the first and last day of the year', function () {
    expect(PeriodEnum::YEAR->for(Carbon::parse('2026-01-01')))->toBe('2026-01-01');
    expect(PeriodEnum::YEAR->for(Carbon::parse('2026-12-31')))->toBe('2026-01-01');
});

// days()

it('returns 7 days for WEEK regardless of date', function () {
    expect(PeriodEnum::WEEK->days(Carbon::parse('2026-03-04')))->toBe(7);
    expect(PeriodEnum::WEEK->days())->toBe(7);
});

it('returns the correct number of days in the month for MONTH', function () {
    expect(PeriodEnum::MONTH->days(Carbon::parse('2026-02-01')))->toBe(28); // non-leap year
    expect(PeriodEnum::MONTH->days(Carbon::parse('2024-02-01')))->toBe(29); // leap year
    expect(PeriodEnum::MONTH->days(Carbon::parse('2026-03-01')))->toBe(31);
});

it('accepts a date string for days()', function () {
    expect(PeriodEnum::MONTH->days('2026-03-01'))->toBe(31);
});

it('uses today when no date is provided to days()', function () {
    expect(PeriodEnum::WEEK->days())->toBe(7);
});

it('returns the correct number of days in each quarter for QUARTER', function () {
    expect(PeriodEnum::QUARTER->days(Carbon::parse('2026-01-01')))->toBe(90); // Q1 non-leap
    expect(PeriodEnum::QUARTER->days(Carbon::parse('2024-01-01')))->toBe(91); // Q1 leap year
    expect(PeriodEnum::QUARTER->days(Carbon::parse('2026-04-01')))->toBe(91); // Q2
    expect(PeriodEnum::QUARTER->days(Carbon::parse('2026-07-01')))->toBe(92); // Q3
    expect(PeriodEnum::QUARTER->days(Carbon::parse('2026-10-01')))->toBe(92); // Q4
});

it('returns the correct number of days in the year for YEAR', function () {
    expect(PeriodEnum::YEAR->days(Carbon::parse('2026-01-01')))->toBe(365); // non-leap
    expect(PeriodEnum::YEAR->days(Carbon::parse('2024-01-01')))->toBe(366); // leap year
});

it('accepts a Carbon instance for QUARTER and YEAR days()', function () {
    expect(PeriodEnum::QUARTER->days(Carbon::parse('2026-04-01')))->toBe(91);
    expect(PeriodEnum::YEAR->days(Carbon::parse('2026-01-01')))->toBe(365);
});

// weeks()

it('returns 1 week for WEEK', function () {
    expect(PeriodEnum::WEEK->weeks())->toBe(1);
});

it('returns days divided by 7 for MONTH', function () {
    $date = Carbon::parse('2026-03-01'); // 31 days

    expect(PeriodEnum::MONTH->weeks($date))->toBe((int) (31 / 7));
});

it('returns days divided by 7 for QUARTER', function () {
    $date = Carbon::parse('2026-04-01'); // Q2 = 91 days

    expect(PeriodEnum::QUARTER->weeks($date))->toBe((int) (91 / 7));
});

it('returns days divided by 7 for YEAR', function () {
    expect(PeriodEnum::YEAR->weeks(Carbon::parse('2026-01-01')))->toBe((int) (365 / 7));
    expect(PeriodEnum::YEAR->weeks(Carbon::parse('2024-01-01')))->toBe((int) (366 / 7));
});

// tryFrom()

it('resolves a valid period string to the correct case', function () {
    expect(PeriodEnum::tryFrom('periods.week'))->toBe(PeriodEnum::WEEK);
    expect(PeriodEnum::tryFrom('periods.month'))->toBe(PeriodEnum::MONTH);
    expect(PeriodEnum::tryFrom('periods.quarter'))->toBe(PeriodEnum::QUARTER);
    expect(PeriodEnum::tryFrom('periods.year'))->toBe(PeriodEnum::YEAR);
});

it('returns null for an unrecognised period string', function () {
    expect(PeriodEnum::tryFrom('periods.decade'))->toBeNull();
});
