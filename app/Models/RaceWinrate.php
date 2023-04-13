<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceWinrate extends Model
{
    use HasFactory;

    protected $table = 'race_winrates';

    protected $fillable = [
        'total_id',
        'my_race',
        'enemy_race',
        'win_percentage',
        'games_played',
        'matchup_shorthand',
    ];

    public function total(): BelongsTo
    {
        return $this->belongsTo(Total::class);
    }
}
