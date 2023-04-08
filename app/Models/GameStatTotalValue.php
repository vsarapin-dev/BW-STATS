<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameStatTotalValue extends Model
{
    use HasFactory;

    protected $table = 'game_stat_total_values';

    protected $fillable = [
        'user_id',
        'total_games',
        'real_wins',
        'general_wins',
    ];
}
