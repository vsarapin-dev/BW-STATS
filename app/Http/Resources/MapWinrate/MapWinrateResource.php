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
            'map_id' => $this->map->name,
            'stats' => $this->stats,
            'games_played' => $this->games_played,
        ];
    }
}
