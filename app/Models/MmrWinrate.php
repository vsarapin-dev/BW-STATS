<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MmrWinrate extends Model
{
    use HasFactory;

    protected $table = 'mmr_winrates';

    protected $fillable = [
        'total_id',
        'mmr_rank_id',
        'win_percentage',
    ];

    public function total(): BelongsTo
    {
        return $this->belongsTo(Total::class);
    }

    public function mmrRank(): BelongsTo
    {
        return $this->belongsTo(MmrRank::class);
    }
}
