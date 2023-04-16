<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Map extends Model
{
    use HasFactory;

    protected $table = 'maps';

    protected $fillable = [
        'name',
    ];

    public function gameStat(): HasMany
    {
        return $this->hasMany(GameStat::class);
    }

    public function bestMap(): HasMany
    {
        return $this->hasMany(BestMap::class);
    }

    public function mapWinrate(): HasMany
    {
        return $this->hasMany(MapWinrate::class);
    }

    public function mapRace(): HasMany
    {
        return $this->hasMany(MapRace::class);
    }
}
