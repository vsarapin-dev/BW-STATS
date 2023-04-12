<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneralStats extends Model
{
    use HasFactory;

    protected $table = 'general_stats';

    protected $fillable = [
        'total_id',
        'games_count',
        'general_stats_count',
        'real_stats_count',
        'general_winrate_percent',
        'winrate_real_percent',
        'smurfs_count',
        'smurfs_percent',
        'wo_count',
        'wo_percent',
        'drop_count',
        'drop_percent',
        'draw_count',
        'draw_percent',
    ];

    public function total(): BelongsTo
    {
        return $this->belongsTo(Total::class);
    }
}
