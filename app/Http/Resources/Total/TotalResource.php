<?php

namespace App\Http\Resources\Total;

use App\Http\Resources\BestMap\BestMapResource;
use App\Http\Resources\GeneralStats\GeneralStatsResource;
use App\Http\Resources\MapWinrate\MapWinrateResource;
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

        return [
            'bestMaps' => $bestMaps,
            'mapWinrate' => $mapWinrate,
            'raceWinrate' => $raceWinrate,
            'mmrWinrate' => $mmrWinrate,
            'generalStats' => count($generalStats) > 0 ? $generalStats[0] : null,
        ];
    }
}
