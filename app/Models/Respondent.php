<?php

namespace App\Models;

use App\Traits\HasChart;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use NicoBleiler\Passphrase\Facades\Passphrase;

class Respondent extends Model
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
        'team_id',
        'passphrase',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    protected function passphrase(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => self::hashPassphrase($value),
        );
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    public function surveySubmitted()
    {
        return $this->surveys()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count() > 0;
    }

    public static function newPassphrase(int $words = 2): string
    {
        do {
            $result = Passphrase::generate(config('passphrase_words'), includeNumber: true);
        } while (self::passphraseExists($result));

        return $result;
    }

    public static function hashPassphrase(string $passphrase): string
    {
        if (! config('appex.passphrase.pepper')) {
            throw new \Exception('Missing PASSPHRASE_PEPPER value.');
        }

        return hash('sha256', config('appex.passphrase.pepper').$passphrase);
    }

    public static function findByPassphrase(string $passphrase): ?self
    {
        return self::wherePassphrase(self::hashPassphrase($passphrase))->first();
    }

    public static function passphraseExists(string $passphrase): bool
    {
        return self::wherePassphrase(self::hashPassphrase($passphrase))->exists();
    }
}
