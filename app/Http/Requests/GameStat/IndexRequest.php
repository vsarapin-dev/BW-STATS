<?php

namespace App\Http\Requests\GameStat;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'sometimes|integer',
            'itemsPerPage' => 'sometimes|integer',
            'season_id' => 'sometimes|exists:seasons,id',
            'sort_by' => 'required',
            'sort_desc' => 'required',
            'enemy_login' => 'sometimes|exists:game_stats,enemy_login',
            'enemy_mmr_between' => 'sometimes|required|array',
            'enemy_race_id' => 'sometimes|exists:races,id',
            'enemy_random_race_id' => 'sometimes|exists:races,id',
            'global_comment' => 'sometimes|exists:game_stats,global_comment',
            'map_id' => 'sometimes|exists:maps,id',
            'my_race_id' => 'sometimes|exists:races,id',
            'result_comment' => 'sometimes|exists:game_stats,result_comment',
            'result_id' => 'sometimes|exists:results,id',
        ];
    }
}
