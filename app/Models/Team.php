<?php

namespace App\Models;

use App\Traits\HasChart;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasChart;
    use HasFactory;
    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'user_id',
        'name',
        'max_respondents',
        'active',
        'likert_scale',
        'questions',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'questions' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function respondents(): HasMany
    {
        return $this->hasMany(Respondent::class);
    }

    public function complete(): bool
    {
        return $this->max_respondents === $this->respondents->count();
    }

    public function available(): int
    {
        return $this->max_respondents - $this->respondents->count();
    }

    public function nextSurveyDate(?Carbon $now = null): Carbon
    {
        $now = ($now?->copy() ?? now())->setTimeFromTimeString($this->created_at->format('H:i'));
        $daysSinceBeginAt = (int) $this->created_at->diffInDays($now);
        $daysSinceLastSurvey = $daysSinceBeginAt % 7/* $this->days_between_surveys */;
        $daysUntilNextSurvey = 7/* $this->days_between_surveys */ - $daysSinceLastSurvey;

        return $now->addDays($daysUntilNextSurvey);
    }

    public function nextSurveyBeginDate(?Carbon $now = null)
    {
        return $this->nextSurveyDate($now)->subDays(7/* $this->days_between_surveys */);
    }

    public function nextSurveyEndDate(?Carbon $now = null)
    {
        return $this->nextSurveyDate($now);
    }
}
