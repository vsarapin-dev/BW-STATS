<?php

namespace App\Http\Resources\GameStat;

use App\Http\Resources\Season\SeasonResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GameStatResource extends JsonResource
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
            'id' => $this->id,
            'game_number' => $this->game_number,
            'map' => $this->map->name,
            'enemy_nickname' => $this->enemy_nickname,
            'enemy_login' => $this->enemy_login,
            'enemy_is_random' => $this->enemy_is_random,
            'my_race' => $this->my_race,
            'enemy_race' => $this->enemy_race,
            'enemy_current_mmr' => $this->enemy_current_mmr,
            'enemy_max_mmr' => $this->enemy_max_mmr,
            'result' => $this->result->name,
            'result_comment' => $this->result_comment,
            'global_comment' => $this->global_comment,
            'is_smurf' => $this->is_smurf,
            'is_leaver' => $this->is_leaver,
            'is_not_calibrated' => $this->is_not_calibrated,
            'is_dropped' => $this->is_dropped,
            'matchup' => $this->matchup,
        ];
    }
}
