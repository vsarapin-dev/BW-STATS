<?php

namespace App\Http\Resources\MapWinrate;

use Illuminate\Http\Resources\Json\JsonResource;

class MapWinrateResource extends JsonResource
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
            'map' => $this->map->name,
            'wins' => $this->wins,
            'losses' => $this->losses,
            'win_percentage' => round($this->win_percentage, 2),
            'games_played' => $this->games_played,
        ];
    }
}
