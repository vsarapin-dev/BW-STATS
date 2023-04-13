<?php

namespace App\Http\Resources\MmrWinrate;

use Illuminate\Http\Resources\Json\JsonResource;

class MmrWinrateResource extends JsonResource
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
            'rankName' => $this->mmrRank->rank_name,
            'from' => $this->mmrRank->from,
            'to' => $this->mmrRank->to,
        ];
    }
}
