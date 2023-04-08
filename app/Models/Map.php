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
}
