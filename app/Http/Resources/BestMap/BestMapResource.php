<?php

namespace App\Http\Resources\BestMap;

use Illuminate\Http\Resources\Json\JsonResource;

class BestMapResource extends JsonResource
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
            'map_name' => $this->map->name,
            'wins' => $this->wins,
            'losses' => $this->losses,
            'win_percentage' => $this->win_percentage,
        ];
    }
}
