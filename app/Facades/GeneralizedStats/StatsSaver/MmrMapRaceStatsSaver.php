<?php


namespace App\Facades\GeneralizedStats\StatsSaver;


use App\Models\Map;
use App\Models\MmrMapRace;
use App\Models\MmrRank;
use App\Models\Race;
use Illuminate\Support\Facades\DB;
use Throwable;

class MmrMapRaceStatsSaver extends BaseStatsSaver
{
    public function computeAndSaveData()
    {
        $arr = $this->computeAllValues();
        $this->saveAllValues($arr);
    }

    private function computeAllValues()
    {
        $seasonId = $this->seasonId;
        $userId = $this->userId;

        $result = Map::with(['gameStat' => function ($query) use ($seasonId, $userId) {
            $query->whereUserId($userId)
                ->whereSeasonId($seasonId)
                ->with('result')
                ->whereHas('result', function ($q) {
                    $q->select('name')
                        ->whereIn('name', ['W', 'L']);
                });
        }])
            ->get();

        $races = Race::get()->pluck('id', 'name')->toArray();
        $mmrRanks = MmrRank::get();

        $res = [];
        $groupedByRanks = [];

        foreach ($result as $map) {
            $groupedByRanks = [];
            foreach ($mmrRanks as $rank) {
                $groupKey = $map->name . "-" . $rank->rank_name;
                $groupedByRanks[$groupKey] = [];
                foreach ($map->gameStat as $row) {
                    if (($row->enemy_max_mmr >= $rank->from) && ($row->enemy_max_mmr <= $rank->to)) {
                        $myRace = substr(ucfirst(array_search($row->my_race_id, $races)), 0, 1);
                        $enemyRace = substr(ucfirst(array_search($row->enemy_race_id, $races)), 0, 1);
                        $matchup = "{$myRace}v{$enemyRace}";
                        $key = $matchup;
                        if (!isset($groupedByRanks[$groupKey][$key])) {
                            $groupedByRanks[$groupKey][$key] = [
                                'W' => 0,
                                'L' => 0,
                                'matchup' => $matchup,
                                'map' => $map->name,
                                'win_percentage' => 0,
                                'rank_name' => $rank->rank_name,
                                'count' => 0,
                            ];
                        }
                        $groupedByRanks[$groupKey][$key][$row->result->name]++;
                        $groupedByRanks[$groupKey][$key]['win_percentage'] = round($groupedByRanks[$groupKey][$key]['W'] / ($groupedByRanks[$groupKey][$key]['W'] + $groupedByRanks[$groupKey][$key]['L']) * 100, 2);
                        $groupedByRanks[$groupKey][$key]['count'] = $groupedByRanks[$groupKey][$key]['W'] + $groupedByRanks[$groupKey][$key]['L'];
                    }
                }
            }
            array_push($res, $groupedByRanks);
        }
        return $res;
    }

    private function saveAllValues($arr)
    {
        DB::beginTransaction();
        try {
            if (count($arr) > 0)
            {
                foreach ($arr as $map)
                {
                    foreach ($map as $mapRanks)
                    {
                        foreach ($mapRanks as $row)
                        {
                            MmrMapRace::create([
                                'total_id' => $this->totalTableId,
                                'map_name' => $row['map'],
                                'rank_name' => $row['rank_name'],
                                'matchup_shorthand' => $row['matchup'],
                                'total_losses' => $row['L'],
                                'total_wins' => $row['W'],
                                'win_percentage' => $row['win_percentage'],
                            ]);
                        }
                    }
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }
    }
}
