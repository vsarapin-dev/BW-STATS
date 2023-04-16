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
            'page' => 'nullable|integer',
            'itemsPerPage' => 'nullable|integer',
            'season_id' => 'nullable|exists:seasons,id',
            'sort_by' => 'required',
            'sort_desc' => 'required',
        ];
    }
}
