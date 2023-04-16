<?php


namespace App\Facades\GeneralizedStats\StatsSaver;


use App\Models\GameStat;
use App\Models\MmrRank;
use App\Models\MmrWinrate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class MmrWinrateSaver extends BaseStatsSaver
{

    public function computeAndSaveData()
    {
        $arr = $this->computeAllValues();
        $this->saveAllValues($arr);
    }

    private function computeAllValues()
    {
        $mmrRanks = MmrRank::all();

        $seasonId = $this->seasonId;
        $userId = $this->userId;

        return $mmrRanks->map(function ($mmrRange) use ($userId, $seasonId) {
            $res = GameStat::whereUserId($userId)
                ->whereSeasonId($seasonId)
                ->whereBetween('enemy_max_mmr', [$mmrRange->from, $mmrRange->to])
                ->join('results', 'game_stats.result_id', '=', 'results.id')
                ->whereIn('results.name', ['W', 'L'])
                ->select(DB::raw('ROUND(
                    (sum(case when results.name = "W" then 1 else 0 end) /
                    (sum(case when results.name = "W" then 1 else 0 end) +
                    sum(case when results.name = "L" then 1 else 0 end)) *
                    100), 2) as wins_percent'),
                )
                ->first();
            return [
                'wins' => $res ? $res->wins_percent : 0,
                'mmr_rank_id' => $mmrRange->id,
            ];
        });
    }

    private function saveAllValues($arr)
    {
        DB::beginTransaction();
        try {
            if (count($arr) > 0) {
                foreach ($arr as $mmrWinrate) {
                    MmrWinrate::create([
                        'total_id' => $this->totalTableId,
                        'mmr_rank_id' => $mmrWinrate['mmr_rank_id'],
                        'win_percentage' => $mmrWinrate['wins'],
                    ]);
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }
    }
}
