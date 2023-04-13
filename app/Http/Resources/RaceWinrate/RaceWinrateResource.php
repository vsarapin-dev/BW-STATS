<?php

namespace App\Http\Resources\RaceWinrate;

use Illuminate\Http\Resources\Json\JsonResource;

class RaceWinrateResource extends JsonResource
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
            'winPercentage' => $this->win_percentage,
            'gamesPlayed' => $this->games_played,
            'matchupShorthand' => $this->matchup_shorthand,
        ];
    }
}
