<?php


namespace App\Facades\GeneralizedStats\StatsSaver;


use App\Models\GameStat;
use App\Models\MapWinrate;
use Illuminate\Support\Facades\DB;
use Throwable;

class MapWinrateStatsSaver extends BaseStatsSaver
{

    public function computeAndSaveData()
    {
        $arr = $this->computeAllValues();
        $this->saveAllValues($arr);
    }

    private function computeAllValues()
    {
        return GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->join('maps as map', 'map.id', '=', 'game_stats.map_id')
            ->join('results', 'game_stats.result_id', '=', 'results.id')
            ->select(
                DB::raw('count(*) as games_played'),
                DB::raw('sum(case when results.name = "W" then 1 else 0 end) as wins_on_map'),
                DB::raw('sum(case when results.name = "L" then 1 else 0 end) as loss_on_map'),
                DB::raw('
                    ROUND(
                    (sum(case when results.name = "W" then 1 else 0 end) /
                    (sum(case when results.name = "W" then 1 else 0 end) +
                    sum(case when results.name = "L" then 1 else 0 end)) *
                    100), 2) as wins_percent'),
                'map.name as name',
                'map_id')
            ->whereIn('results.name', ['W', 'L'])
            ->groupBy('map_id')
            ->orderBy('name')
            ->get();
    }

    private function saveAllValues($arr)
    {
        DB::beginTransaction();
        try {
            if (count($arr) > 0)
            {
                foreach ($arr as $mapWinrate)
                {
                    MapWinrate::create([
                        'total_id' => $this->totalTableId,
                        'map_id' => $mapWinrate->map_id,
                        'wins' => $mapWinrate->wins_on_map,
                        'losses' => $mapWinrate->loss_on_map,
                        'win_percentage' => $mapWinrate->wins_percent,
                        'games_played' => $mapWinrate->games_played,
                    ]);
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }
    }
}
