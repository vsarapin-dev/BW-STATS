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
        $arr = [];
        GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->
        return $arr;
    }

    private function saveAllValues($arr)
    {
        DB::beginTransaction();
        try {
            MapWinrate::create([
                'total_id' => $this->totalTableId,
//                'map_id' =>,
//                'stats' =>,
//                'games_played' =>,
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }
    }
}
