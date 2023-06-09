<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameStat extends Model
{
    use HasFactory, Filterable;

    protected $table = 'game_stats';

    public bool $afterCommit = true;

    public const COLOR_RESULT_DEFAULT = 'white';
    public const COLOR_RESULT_WIN = 'green';
    public const COLOR_RESULT_LOSS = 'red';

    public const COLOR_MAP = [
        'W' => self::COLOR_RESULT_WIN,
        'L' => self::COLOR_RESULT_LOSS,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'game_number',
        'season_id',
        'map_id',
        'enemy_nickname',
        'enemy_login',
        'my_race_id',
        'enemy_random_race_id',
        'enemy_race_id',
        'enemy_current_mmr',
        'enemy_max_mmr',
        'result_id',
        'result_comment',
        'global_comment',
        'is_smurf',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    public function myRace(): BelongsTo
    {
        return $this->belongsTo(Race::class, 'my_race_id');
    }

    public function enemyRace(): BelongsTo
    {
        return $this->belongsTo(Race::class, 'enemy_race_id');
    }

    public function enemyRandomRace(): BelongsTo
    {
        return $this->belongsTo(Race::class, 'enemy_random_race_id');
    }

    public function getMatchupAttribute(): string
    {

        $myRace = strtoupper($this->myRace->name[0]);
        $enemyRace = strtoupper($this->enemyRace->name[0]);
        $addIfRandomRace = $this->enemyRandomRace !== null ? "({$this->enemyRandomRace->name[0]})" : "";

        return "{$myRace}v{$enemyRace} {$addIfRandomRace}";
    }
}
