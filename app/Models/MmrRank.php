<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MmrRank extends Model
{
    use HasFactory;

    protected $table = 'mmr_ranks';

    protected $fillable = [
        'from',
        'to',
        'rank_name',
    ];

    public function mmrWinrate(): HasMany
    {
        return $this->hasMany(MmrWinrate::class);
    }
}
