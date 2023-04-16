<?php


namespace App\Facades\GeneralizedStats\StatsSaver;


use App\Models\GameStat;
use App\Models\Map;
use App\Models\MapRace;
use Illuminate\Support\Facades\DB;
use Throwable;

class MapRaceStatsSaver extends BaseStatsSaver
{

    public function computeAndSaveData()
    {
        $arr = $this->computeAllValues();
        $this->saveAllValues($arr);
    }

    private function computeAllValues()
    {
        $maps = Map::orderBy('name')->get();

        $seasonId = $this->seasonId;
        $userId = $this->userId;

        return $maps->map(function ($map) use ($seasonId, $userId) {
            return GameStat::whereUserId($userId)
                ->whereSeasonId($seasonId)
                ->whereMapId($map->id)
                ->join('maps as map', 'game_stats.map_id', '=', 'map.id')
                ->join('races as my_race', 'game_stats.my_race_id', '=', 'my_race.id')
                ->join('races as enemy_race', 'game_stats.enemy_race_id', '=', 'enemy_race.id')
                ->join('results', 'game_stats.result_id', '=', 'results.id')
                ->whereIn('results.name', ['W', 'L'])
                ->select(
                    DB::raw('count(*) as games_played'),
                    DB::raw('sum(case when results.name = "W" then 1 else 0 end) as total_wins'),
                    DB::raw('sum(case when results.name = "L" then 1 else 0 end) as total_losses'),
                    DB::raw('ROUND(
                sum(case when results.name = "W" then 1 else 0 end) /
                (sum(case when results.name = "W" then 1 else 0 end) +
                sum(case when results.name = "L" then 1 else 0 end)) *
                100, 2) as win_percentage'),
                    'my_race.id as my_race_id',
                    'enemy_race.id as enemy_race_id',
                    'map_id',
                    'map.name as map_name',
                )
                ->groupBy('my_race.name', 'enemy_race.name', 'my_race.id', 'enemy_race.id', 'map_id', 'map_name')
                ->orderByDesc('win_percentage')
                ->selectRaw('CONCAT(LEFT(my_race.name, 1), "v", LEFT(enemy_race.name, 1)) as matchup_shorthand')
                ->get();
        });
    }

    private function saveAllValues($arr)
    {
        DB::beginTransaction();
        try {
            if (count($arr) > 0)
            {
                foreach ($arr as $key)
                {
                    foreach ($key as $mapRace)
                    {
                        MapRace::create([
                            'total_id' => $this->totalTableId,
                            'map_id' => $mapRace['map_id'],
                            'enemy_race_id' => $mapRace['enemy_race_id'],
                            'my_race_id' => $mapRace['my_race_id'],
                            'map_name' => $mapRace['map_name'],
                            'matchup_shorthand' => $mapRace['matchup_shorthand'],
                            'total_losses' => $mapRace['total_losses'],
                            'total_wins' => $mapRace['total_wins'],
                            'win_percentage' => $mapRace['win_percentage'],
                            'games_played' => $mapRace['games_played'],
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }
    }
}
