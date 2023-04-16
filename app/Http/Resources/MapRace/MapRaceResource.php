<?php

namespace App\Http\Resources\MapRace;

use Illuminate\Http\Resources\Json\JsonResource;

class MapRaceResource extends JsonResource
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
            'matchupShorthand' => $this->matchup_shorthand,
            'winPercentage' => $this->win_percentage,
        ];
    }
}
