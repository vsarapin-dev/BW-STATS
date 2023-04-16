<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MmrMapRace extends Model
{
    use HasFactory;

    protected $table = 'mmr_map_races';

    protected $fillable = [
        'total_id',
        'map_name',
        'rank_name',
        'matchup_shorthand',
        'total_losses',
        'total_wins',
        'win_percentage',
    ];

    public function mmrMapRace(): BelongsTo
    {
        return $this->belongsTo(Total::class);
    }
}
