<?php

namespace App\Observers;

use App\Models\GameStat;
use App\Models\GameStatTotalValue;
use Illuminate\Database\Eloquent\Model;

class GameStatObserver
{
    /**
     * Handle the GameStat "created" event.
     *
     * @param \App\Models\GameStat $gameStat
     * @return void
     */
    public function created(GameStat $gameStat)
    {
        //
    }

    public function saved(GameStat $gameStat)
    {
        GameStatTotalValue::updateOrCreate(
            [
                'user_id' => $gameStat->user_id
            ],
            [
                'total_games' => GameStat::where('user_id', $gameStat->user_id)->count(),
                'general_wins' => GameStat::where('user_id', $gameStat->user_id)
                    ->whereHas('result', function ($query) {
                        return $query->whereIn('name', ['W', 'W/O']);
                    })->get()->count(),
                'real_wins' => GameStat::where('user_id', $gameStat->user_id)
                    ->whereHas('result', function ($query) {
                        return $query->where('name', 'W');
                    })->count(),
            ]
        );
    }

    /**
     * Handle the GameStat "updated" event.
     *
     * @param \App\Models\GameStat $gameStat
     * @return void
     */
    public function updated(GameStat $gameStat)
    {
        //
    }

    /**
     * Handle the GameStat "deleted" event.
     *
     * @param \App\Models\GameStat $gameStat
     * @return void
     */
    public function deleted(GameStat $gameStat)
    {
        //
    }

    /**
     * Handle the GameStat "restored" event.
     *
     * @param \App\Models\GameStat $gameStat
     * @return void
     */
    public function restored(GameStat $gameStat)
    {
        //
    }

    /**
     * Handle the GameStat "force deleted" event.
     *
     * @param \App\Models\GameStat $gameStat
     * @return void
     */
    public function forceDeleted(GameStat $gameStat)
    {
        //
    }
}
