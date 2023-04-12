<?php


namespace App\Facades\GeneralizedStats\StatsSaver;


use App\Models\BestMap;
use App\Models\Map;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class BestMapsStatsSaver extends BaseStatsSaver
{
    public function computeAndSaveData()
    {
        $bestMaps = $this->computeBestMaps();
        $this->saveBestMaps($bestMaps);
    }

    private function computeBestMaps(): ?Collection
    {
        return Map::select('maps.name', 'maps.id', DB::raw('SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) AS wins, SUM(CASE WHEN results.name = "L" THEN 1 ELSE 0 END) AS losses, (SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) / (SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) + SUM(CASE WHEN results.name = "L" THEN 1 ELSE 0 END))) * 100 AS win_percentage'))
            ->join('game_stats', 'maps.id', '=', 'game_stats.map_id')
            ->join('results', 'game_stats.result_id', '=', 'results.id')
            ->where('game_stats.user_id', $this->userId)
            ->where('game_stats.season_id', $this->seasonId)
            ->groupBy('maps.id')
            ->orderByDesc('win_percentage')
            ->get();


    }

    private function saveBestMaps($bestMaps)
    {
        DB::beginTransaction();
        try {
            if (count($bestMaps) > 0) {
                $bestMaps->each(function ($mapResult) {
                    BestMap::create([
                        'total_id' => $this->totalTableId,
                        'map_id' => $mapResult->id,
                        'wins' => $mapResult->wins,
                        'losses' => $mapResult->losses,
                        'win_percentage' => $mapResult->win_percentage,
                    ]);
                });
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }
    }
}
