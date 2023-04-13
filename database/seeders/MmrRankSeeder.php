<?php

namespace Database\Seeders;

use App\Models\MmrRank;
use Illuminate\Database\Seeder;

class MmrRankSeeder extends Seeder
{
    public array $mmrRanks = [
        'C' => [
            'from' => 0,
            'to' => 1750,
        ],
        'B' => [
            'from' => 1800,
            'to' => 1900,
        ],
        'A' => [
            'from' => 1950,
            'to' => 10000,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->mmrRanks as $key => $value)
        {
            MmrRank::create([
                'rank_name' => $key,
                'from' => $value['from'],
                'to' => $value['to'],
            ]);
        }
    }
}
