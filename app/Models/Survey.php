<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    use HasFactory;
    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'respondent_id',
        'responses',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'responses' => 'encrypted:array',
        ];
    }

    public function respondent(): BelongsTo
    {
        return $this->belongsTo(Respondent::class);
    }

    public static function between(Team|Respondent|null $model, Carbon $begin_at, Carbon $end_at)
    {
        return self::where(function ($query) use ($model) {
            if ($model instanceof Respondent) {
                $query->where('respondent_id', $model->id);
            } elseif ($model instanceof Team) {
                $query->whereIn('respondent_id', $model->respondents->pluck('id'));
            }
        })
            ->whereBetween('created_at', [$begin_at, $end_at])
            ->orderBy('id', 'asc') // ulid is sortable
            ->get();
    }
}
