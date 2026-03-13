<?php

namespace App\Support\Charts;

use App\Enums\PeriodEnum;
use App\Models\Respondent;
use App\Models\Survey;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class Chart
{
    const DEFAULT_BEGIN_DATE = '2024-07-31 04:00:00';

    private Team $team;

    private ?Respondent $respondent;

    private ?Collection $surveys = null;

    public function __construct(
        private Team|Respondent $model,
        private Carbon|string|null $begin_at = null,
        private Carbon|string|null $end_at = null,
        private PeriodEnum $period,
        private int $question,
        private bool $weighted,
    ) {
        if ($model instanceof Respondent) {
            $this->team = $model->team;
            $this->respondent = $model;
        } else {
            $this->team = $model;
            $this->respondent = null;
        }

        if ($begin_at === null) {
            $this->begin_at = Carbon::parse(self::DEFAULT_BEGIN_DATE);
        } elseif (is_string($begin_at)) {
            $this->begin_at = Carbon::parse($begin_at);
        }

        if ($end_at === null) {
            $this->end_at = now();
        } elseif (is_string($end_at)) {
            $this->end_at = Carbon::parse($end_at);
        }
    }

    public function getBeginAt(): Carbon
    {
        return $this->begin_at;
    }

    public function getEndAt(): Carbon
    {
        return $this->end_at;
    }

    public function respondents(): Collection
    {
        return $this->team->respondents;
    }

    public function surveys()
    {
        if ($this->surveys === null) {
            $this->surveys = Survey::between($this->model, $this->begin_at, $this->end_at);
        }

        return $this->surveys;
    }

    private function getLineData(Collection $surveysByPeriod)
    {
        $result = [];

        foreach ($surveysByPeriod as $date => $surveys) {
            $average = $this->average($surveys);

            if ($this->weighted) {
                $average = $this->weightedAverage($average, $date, $surveys);
            }

            $result[] = [
                'date' => Carbon::parse($date),
                'average' => round($average, 2),
                'count' => $surveys->count(),
            ];
        }

        return $result;
    }

    private function average(EloquentCollection $surveys)
    {
        return $surveys->average(fn ($survey) => $survey->responses[$this->question]);
    }

    private function weightedAverage(float $average, string $date, EloquentCollection $surveys)
    {
        $maxSurveys = $this->team->respondents->count() * $this->period->weeks($date);

        if ($maxSurveys === 0) {
            return 0;
        }

        return $average * $surveys->count() / $maxSurveys;
    }

    public function toArray(): array
    {
        $result = [];

        $surveysByPeriod = $this->surveys()->groupBy(fn ($row) => $this->period->for($row->created_at));

        $result[] = (object) [
            'line' => $this->team->questions[$this->question],
            'data' => $this->getLineData($surveysByPeriod),
        ];

        return (new ChartJs($result, ! $this->weighted))->toArray();
    }
}
