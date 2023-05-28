<?php

namespace App\Http\Resources\GeneralStats;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralStatsResource extends JsonResource
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
            'gamesCount' => $this->games_count,
            'generalStatsCount' => $this->general_stats_count,
            'realStatsCount' => $this->real_stats_count,
            'generalWinratePercent' => $this->general_winrate_percent,
            'winrateRealPercent' => $this->winrate_real_percent,
            'smurfsCount' => $this->smurfs_count,
            'smurfsPercent' => $this->smurfs_percent,
            'woCount' => $this->wo_count,
            'woPercent' => $this->wo_percent,
            'dropCount' => $this->drop_count,
            'dropPercent' => $this->drop_percent,
            'drawCount' => $this->draw_count,
            'drawPercent' => $this->draw_percent,
        ];
    }
}
