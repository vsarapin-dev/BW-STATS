<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    protected $table = 'races';

    protected $fillable = [
        'name',
    ];

    public function myRace()
    {
        $this->hasMany(GameStat::class, 'my_race_id', 'id');
    }

    public function enemyRace()
    {
        $this->hasMany(GameStat::class, 'enemy_race_id', 'id');
    }

    public function enemyRandomRace()
    {
        $this->hasMany(GameStat::class, 'enemy_random_race_id', 'id');
    }
}
