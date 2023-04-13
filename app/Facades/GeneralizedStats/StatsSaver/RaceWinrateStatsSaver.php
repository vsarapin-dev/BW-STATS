<?php


namespace App\Facades\GeneralizedStats\StatsSaver;


use App\Models\GameStat;
use App\Models\RaceWinrate;
use Illuminate\Support\Facades\DB;
use Throwable;

class RaceWinrateStatsSaver extends BaseStatsSaver
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
            ->join('races as my_race', 'game_stats.my_race_id', '=', 'my_race.id')
            ->join('races as enemy_race', 'game_stats.enemy_race_id', '=', 'enemy_race.id')
            ->join('results', 'game_stats.result_id', '=', 'results.id')
            ->whereIn('results.name', ['W', 'L', 'DROP', 'DRAW'])
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
            )
            ->groupBy('my_race.name', 'enemy_race.name', 'my_race.id', 'enemy_race.id')
            ->orderByDesc('win_percentage')
            ->selectRaw('CONCAT(LEFT(my_race.name, 1), "v", LEFT(enemy_race.name, 1)) as matchup_shorthand')
            ->get();
    }

    private function saveAllValues($arr)
    {
        DB::beginTransaction();
        try {
            if (count($arr) > 0) {
                foreach ($arr as $raceWinrate) {
                    RaceWinrate::create([
                        'total_id' => $this->totalTableId,
                        'my_race' => $raceWinrate->my_race_id,
                        'enemy_race' => $raceWinrate->enemy_race_id,
                        'win_percentage' => $raceWinrate->win_percentage,
                        'games_played' => $raceWinrate->games_played,
                        'matchup_shorthand' => $raceWinrate->matchup_shorthand,
                    ]);
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }
    }
}
