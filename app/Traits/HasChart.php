<?php

namespace App\Traits;

use App\Enums\PeriodEnum;
use App\Support\Charts\Chart;
use Carbon\Carbon;

trait HasChart
{
    public function chart(Carbon|string|null $begin_at, Carbon|string|null $end_at, PeriodEnum $period, int $question, bool $weighted): Chart
    {
        return new Chart($this, $begin_at, $end_at, $period, $question, $weighted);
    }
}
