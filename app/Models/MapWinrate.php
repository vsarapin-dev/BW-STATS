<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapWinrate extends Model
{
    use HasFactory;

    protected $table = 'map_winrates';

    protected $fillable = [
        'total_id',
        'map_id',
        'wins',
        'losses',
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
}
