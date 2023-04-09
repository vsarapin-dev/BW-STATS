<?php

namespace Database\Seeders;

use App\Models\Result;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    public array $results = [
        'W',
        'L',
        'W/O',
        'DROP',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->results as $result)
        {
            Result::create([
                'name' => $result,
            ]);
        }
    }
}
