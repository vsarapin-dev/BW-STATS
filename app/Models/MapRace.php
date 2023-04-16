<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapRace extends Model
{
    use HasFactory;

    protected $table = 'map_races';

    protected $fillable = [
        'total_id',
        'map_id',
        'map_name',
        'matchup_shorthand',
        'enemy_race_id',
        'my_race_id',
        'total_losses',
        'total_wins',
        'win_percentage',
        'games_played',
    ];

    public function total(): BelongsTo
    {
        return $this->belongsTo(Total::class);
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    public function enemyRace(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function myRace(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }
}
