<?php


namespace App\Facades\GeneralizedStats;

use App\Facades\GeneralizedStats\StatsSaver\BestMapsStatsSaver;
use App\Facades\GeneralizedStats\StatsSaver\CachedStatsFactory;
use App\Facades\GeneralizedStats\StatsSaver\GeneralStatsSaver;
use App\Facades\GeneralizedStats\StatsSaver\MapWinrateStatsSaver;
use App\Http\Resources\Total\TotalResource;
use App\Models\Total;
use Illuminate\Support\Facades\Auth;

class GeneralizedStats
{
    public function getAllBestStats($seasonId)
    {
        return new TotalResource(Total::whereUserId(Auth::id())->whereSeasonId($seasonId)->first());
    }

    public function setAllBestStats($seasonId, $totalTableId)
    {
        $cachedStatsFactory = new CachedStatsFactory($seasonId, $totalTableId);

        $generalStatsSaver = $cachedStatsFactory->createObject(GeneralStatsSaver::class);
        $bestMapsStatsSaver = $cachedStatsFactory->createObject(BestMapsStatsSaver::class);
        $mapWinrateStatsSaver = $cachedStatsFactory->createObject(MapWinrateStatsSaver::class);

        $generalStatsSaver->computeAndSaveData();
        $bestMapsStatsSaver->computeAndSaveData();
        $mapWinrateStatsSaver->computeAndSaveData();
    }


    /*public function saveEditableData($data): JsonResponse
    {
        GeneralStats::updateOrCreate(
            [
                'user_id' => $this->userId,
                'season_id' => $data['season_id'],
            ],
            $data
        );

        return response()->json(['message' => 'Updated successfully']);
    }*/
}
