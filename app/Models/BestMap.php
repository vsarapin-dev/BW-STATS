<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BestMap extends Model
{
    use HasFactory;

    protected $table = 'best_maps';

    protected $fillable = [
        'total_id',
        'map_id',
        'wins',
        'losses',
        'win_percentage',
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
