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
            'map_id' => $this->map_id,
            'map' => $this->map->name,
            'enemy_nickname' => $this->enemy_nickname,
            'enemy_login' => $this->enemy_login,
            'enemy_is_random' => $this->enemy_is_random,
            'my_race' => $this->my_race,
            'my_race_id' => $this->my_race_id,
            'enemy_race' => $this->enemy_race,
            'enemy_race_id' => $this->enemy_race_id,
            'enemy_current_mmr' => $this->enemy_current_mmr,
            'enemy_max_mmr' => $this->enemy_max_mmr,
            'result_id' => $this->result_id,
            'result' => $this->result->name,
            'result_comment' => $this->result_comment,
            'global_comment' => $this->global_comment,
            'is_smurf' => $this->is_smurf,
            'matchup' => $this->matchup,
        ];
    }
}
