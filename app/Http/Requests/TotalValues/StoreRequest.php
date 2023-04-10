<?php

namespace App\Http\Requests\TotalValues;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'season_id' => 'nullable|required',
            'min_season_mmr' => 'nullable',
            'max_season_mmr' => 'nullable',
            'final_season_mmr' => 'nullable',
            'placement_matches' => 'nullable',
            'season_started' => 'nullable|date_format:Y-m-d',
            'season_ended' => 'nullable|date_format:Y-m-d',
        ];
    }
}
