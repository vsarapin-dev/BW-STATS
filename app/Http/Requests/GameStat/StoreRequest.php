<?php

namespace App\Http\Requests\GameStat;

use App\Rules\RequireRandomRace;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'map_id' => 'required|exists:maps,id',
            'season_id' => 'required|exists:seasons,id',
            'enemy_nickname' => 'nullable',
            'enemy_login' => 'nullable',
            'my_race_id' => 'required|exists:races,id',
            'enemy_race_id' => 'required|exists:races,id',
            'enemy_random_race_id' => ['sometimes', new RequireRandomRace('races', 'name')],
            'enemy_current_mmr' => 'nullable|integer',
            'enemy_max_mmr' => 'nullable|integer',
            'result_id' => 'required|exists:results,id',
            'result_comment' => 'nullable',
            'global_comment' => 'nullable',
        ];
    }
}
