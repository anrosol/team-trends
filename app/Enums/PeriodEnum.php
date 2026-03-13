<?php

namespace App\Enums;

use Carbon\Carbon;

enum PeriodEnum: string
{
    case WEEK = 'periods.week';
    case MONTH = 'periods.month';
    case QUARTER = 'periods.quarter';
    case YEAR = 'periods.year';

    public function for(Carbon $date): string
    {
        return match ($this) {
            self::WEEK => $date->startOfWeek()->format('Y-m-d'),

            self::MONTH => $date->format('Y-m-01'),

            self::QUARTER => $date->format(match ($date->quarter) {
                1 => 'Y-01-01',
                2 => 'Y-04-01',
                3 => 'Y-07-01',
                4 => 'Y-10-01',
            }),

            self::YEAR => $date->format('Y-01-01'),
        };
    }

    public function days(Carbon|string|null $date = null): int
    {
        if ($date === null) {
            $date = now();
        } elseif (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return match ($this) {
            self::WEEK => 7,

            self::MONTH => $date->daysInMonth(),

            self::QUARTER => $date->daysInQuarter(),

            self::YEAR => $date->daysInYear(),
        };
    }

    public function weeks(Carbon|string|null $date = null): int
    {
        return $this->days($date) / 7;
    }
}
