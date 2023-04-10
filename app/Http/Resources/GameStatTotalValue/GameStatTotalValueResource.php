<?php

namespace App\Http\Resources\GameStatTotalValue;

use Illuminate\Http\Resources\Json\JsonResource;

class GameStatTotalValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'bestDataResults' => [
                'percents' => [
                    'smurfPercent' => round($this->smurf_percent, 2),
                    'woPercent' => round($this->wo_percent, 2),
                ],
                'maxStreaks' => [
                    'maxWins' => $this->max_wins,
                    'maxDefeats' => $this->max_defeats,
                ]
            ],
            'gameStatTotalValueResult' => [
                'total_games' => $this->total_games,
                'general_wins' => $this->general_wins,
                'real_wins' => $this->real_wins,
            ]
        ];
    }
}
