<?php

namespace App\Http\Resources\MmrMapRace;

use Illuminate\Http\Resources\Json\JsonResource;

class MmrMapRaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'mapName' => $this->map_name,
            'rankName' => $this->rank_name,
            'matchupShorthand' => $this->matchup_shorthand,
            'totalLosses' => $this->total_losses,
            'totalWins' => $this->total_wins,
            'winPercentage' => $this->win_percentage,
        ];
    }
}
