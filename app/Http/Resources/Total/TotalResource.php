<?php

namespace App\Http\Resources\Total;

use App\Http\Resources\BestMap\BestMapResource;
use App\Http\Resources\GeneralStats\GeneralStatsResource;
use App\Http\Resources\MapWinrate\MapWinrateResource;
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

        return [
            'bestMaps' => $bestMaps,
            'mapWinrate' => $mapWinrate,
            'generalStats' => count($generalStats) > 0 ? $generalStats[0] : null,
        ];
    }
}
