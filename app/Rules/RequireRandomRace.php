<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class RequireRandomRace implements Rule
{
    protected $tableName;
    protected $columnName;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($tableName, $columnName)
    {
        $this->tableName = $tableName;
        $this->columnName = $columnName;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $isRandom = DB::table($this->tableName)
            ->where('id', request()->input('enemy_race_id'))
            ->where($this->columnName, 'Random')
            ->exists();

        if ($isRandom) {
            return !empty($value);
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field is required when the other field is "Random".';
    }
}
