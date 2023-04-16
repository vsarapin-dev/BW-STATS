<?php

namespace App\Http\Resources\Total;

use App\Http\Resources\BestMap\BestMapResource;
use App\Http\Resources\GeneralStats\GeneralStatsResource;
use App\Http\Resources\MapRace\MapRaceResource;
use App\Http\Resources\MapWinrate\MapWinrateResource;
use App\Http\Resources\MmrMapRace\MmrMapRaceResource;
use App\Http\Resources\MmrWinrate\MmrWinrateResource;
use App\Http\Resources\RaceWinrate\RaceWinrateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TotalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $bestMaps = BestMapResource::collection($this->bestMaps);
        $generalStats = GeneralStatsResource::collection($this->generalStats);
        $mapWinrate = MapWinrateResource::collection($this->mapWinrate);
        $raceWinrate = RaceWinrateResource::collection($this->raceWinrate);
        $mmrWinrate = MmrWinrateResource::collection($this->mmrWinrate);
        $mapRace = MapRaceResource::collection($this->mapRace)->collection->groupBy('map_name');
        $mmrMapRace = MmrMapRaceResource::collection($this->mmrMapRace)->collection->groupBy('rank_name')->map(function ($item) {
            return $item->groupBy('map_name');
        });

        return [
            'bestMaps' => count($bestMaps) > 0 ? $bestMaps[0] : null,
            'mapWinrate' => $mapWinrate,
            'raceWinrate' => $raceWinrate,
            'mmrWinrate' => $mmrWinrate,
            'mapRace' => $mapRace,
            'mmrMapRace' => $mmrMapRace,
            'generalStats' => count($generalStats) > 0 ? $generalStats[0] : null,
        ];
    }
}
