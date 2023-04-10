<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameStatTotalValue extends Model
{
    use HasFactory;

    protected $table = 'game_stat_total_values';

    protected $fillable = [
        'user_id',
        'season_id',
        'smurf_percent',
        'wo_percent',
        'max_wins',
        'max_defeats',
        'total_games',
        'general_wins',
        'real_wins',
        'min_season_mmr',
        'max_season_mmr',
        'final_season_mmr',
        'placement_matches',
        'season_started',
        'season_ended',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}
