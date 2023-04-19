<?php

namespace App\Http\Requests\GameStat;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'enemy_login' => 'nullable|exists:game_stats,enemy_login',
            'enemy_max_mmr' => 'nullable|integer',
            'enemy_min_mmr' => 'nullable|integer',
            'enemy_race_id' => 'nullable|exists:races,id',
            'enemy_random_race_id' => 'nullable|exists:races,id',
            'global_comment' => 'nullable|exists:game_stats,global_comment',
            'map_id' => 'nullable|exists:map,id',
            'my_race_id' => 'nullable|exists:races,id',
            'result_comment' => 'nullable|exists:game_stats,result_comment',
            'result_id' => 'nullable|exists:results,id',
            'page' => 'nullable|integer',
            'itemsPerPage' => 'nullable|integer',
            'season_id' => 'nullable|exists:seasons,id',
            'sort_by' => 'required',
            'sort_desc' => 'required',
        ];
    }
}
