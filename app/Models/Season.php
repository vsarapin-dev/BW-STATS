<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Season extends Model
{
    use HasFactory;

    protected $table = 'seasons';

    protected $fillable = [
        'season_number',
    ];

    public function gameStat() : HasMany
    {
        return $this->hasMany(GameStat::class);
    }

    public function totalStats(): HasOne
    {
        return $this->hasOne(GameStatTotalValue::class);
    }
}
