<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Total extends Model
{
    use HasFactory;

    protected $table = 'totals';

    protected $fillable = [
        'user_id',
        'season_id',
    ];

    public function bestMaps(): HasMany
    {
        return $this->hasMany(BestMap::class);
    }

    public function generalStats(): HasMany
    {
        return $this->hasMany(GeneralStats::class);
    }

    public function mapWinrate(): HasMany
    {
        return $this->hasMany(MapWinrate::class);
    }

    public function raceWinrate(): HasMany
    {
        return $this->hasMany(RaceWinrate::class);
    }

    public function mmrWinrate(): HasMany
    {
        return $this->hasMany(MmrWinrate::class);
    }

    public function mapRace(): HasMany
    {
        return $this->hasMany(MapRace::class);
    }

    public function mmrMapRace(): HasMany
    {
        return $this->hasMany(MmrMapRace::class);
    }
}
